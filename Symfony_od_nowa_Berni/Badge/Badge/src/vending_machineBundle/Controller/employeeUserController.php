<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\Transactions;
use vending_machineBundle\Entity\User;
use vending_machineBundle\Form\employeeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;




class employeeUserController extends Controller
{
    /**
     * @Route("/{id}/modifyEmployee" , name="editemployee")
     * @Template("@vending_machine/employee/modifyEmployee.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function modifyEmployeeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:User');
        $employee = $repository->find($id);

        if (!$employee) {
            return new Response('Pracownik o podanym ID nie istnieje');
        }

        $form = $this->createForm(employeeType::class, $employee);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $employee = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->redirect('/showEmployees');
        }
        return ['form' => $form->createView()];
    }

    /**
     * @Route("/showEmployees" , name="showemployees")
     * @Template("@vending_machine/employee/employeeList.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function listOfEmployeeAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('vending_machineBundle:User');

        $users = $repository->findAll();
        $roles = User::hereRoles();

        return[
            'users' => $users,
            'roles' => $roles
        ];
    }

    /**
     * @Route("/showProfile" , name="showprofile")
     * @Template("@vending_machine/employee/showProfile.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function showProfileAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('vending_machineBundle:User');

        $userId = $this->getUser()->getId();
        $user = $repository->findOneById($userId);

        return[
            'user' => $user
        ];
    }

    /**
     * @Route("/payment/{id}", name="deposit", methods="POST")
     * @Security("has_role('ROLE_USER')")
     */

    public function depositAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:User');
        $employee = $repository->find($id);

        $employee->deposit($request->request->get('amount'));           //Korzystam z metody encji employee
        $em = $this->getDoctrine()->getManager();
        $em->persist($employee);
        $em->flush();

        return $this->redirectToRoute('showprofile');
    }

    /**
     * @Route("/moneyBoost", name="moneyboost")
     * @Template("@vending_machine/employee/moneyBoost.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function moneyBoostAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:User');
        $userId = $this->getUser()->getId();
        $user = $repository->findOneById($userId);

        return [
            'user' => $user
        ];
    }

    /**
     * @Route("/buyFromMachine/{user_id}/{product_id}", name="buyfrommachine")
     * @Template("@vending_machine/employee/noEnoughMoney.html.twig")
     * @Security("has_role('ROLE_USER')")
     *
     * Metoda pozwalająca użytkownikowi kupić produkt w automacie.
     * Korzysta z pięciu encji ( machine, User, Distributor, typesOfservices i Transactions ),
     * Wprowadzona została walidacja zarówno ilości produktów w automacie jak i ilośći środków na koncie użytkownika.
     * Dodana została arhiwizacja zakupów encji Transactions.
     * Kwota transakcji zapisywana jest do encji Distributor
     * do typesOfservices zapisywany jest aktualny stan konta dystrybutora jak i sumowane są pieniądze z dystrubutora i kantyny
     */

    public function buyFromMachineAction(Request $request, $user_id, $product_id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->find($user_id);

        $productRepository = $em->getRepository('vending_machineBundle:machine');
        $product = $productRepository->find($product_id);

        $distributorRepository = $em->getRepository('vending_machineBundle:Distributor');
        $distributor = $distributorRepository->findOneById(1);

        $generalServiceRepository = $em->getRepository('vending_machineBundle:typesOfservices');
        $generalServiceDistributor = $generalServiceRepository->findOneById(1);
        $generalServiceCanteen = $generalServiceRepository->findOneById(2);


        // Pobieram z formularza wartość quantity
        $quantity = $request->request->get('quantity');

        // Walidacja ilości kupowanego towaru ( załatwiona też w formularzu)
        if ($quantity > $product->getStock()){

            return new Response('Brak wystarczającej ilośći produktów');
        }

        // Metoda odejmująca zakupioną ilość towaru od stocka
        $product->stockCorrection($quantity);
        $priceBeforeDiscount = $product->getPrice() * $quantity;
        $price = $priceBeforeDiscount - ($priceBeforeDiscount * $user->getDiscount());

        // Walidacja stanu gotówki kupującego z odesłaniem do szablonu informującego o kosztach zamówienia
        if ($user->getCash() < $price){

            return [
              'price' => $price,
              'user' => $user
            ];
        }
        // Metoda pobierająca pieniądze z konta kupującego
        $user->buyFromMachine($price);
        // Zapis do historii operacji
//        $operationHistory = "Kupiłeś ".$quantity. " ".$product->getProductName().", zapłaciłeś ".$price.", na koncie pozostało ".$user->getCash().".  ";
//        $user->addToHistory($operationHistory);
        $user->spentMoneyAdd($price);

        //Teorzenie wpisu w tabeli transactions
        $transaction = new Transactions();
        $transaction->setUser($user);
        $transaction->setMachine($product);
        $transaction->setQuantity($quantity);
        $transaction->setPriceUnit($product->getPrice());
        $transaction->setPriceTotal($price);
        $transaction->setMoneyLeft($user->getCash());
        $now = new \DateTime(date('Y-m-d H:i:s'));
        $transaction->setCreated($now);
        $transaction->setUserDiscount($user->getDiscount());

        //Sprawdzam czy użytkownikowi można nadać rabat

        //Wpłacam pieniądze użytkownika na konto Automatu sprzedażowego
        $distributor->addMoney($price);
        //Linkuję pieniądze automatu do tabeli typesOfservices
        $generalServiceDistributor->cashFromService($price);

        $distributorMoney = $generalServiceDistributor->getCash();
        $canteenMomey= $generalServiceCanteen->getCash();
        $total_cash = $distributorMoney + $canteenMomey;
        $generalServiceCanteen->setTotalCash($total_cash);
        $generalServiceDistributor->setTotalCash($total_cash);
        $product->updateNumberOfSold($quantity);


        $em->persist($transaction);
        $em->persist($user);
        $em->persist($product);
        $em->persist($distributor);
        $em->persist($generalServiceDistributor);
        $em->persist($generalServiceCanteen);
        $em->flush();

        return $this->render('@vending_machine/employee/summaryOfPurchases.html.twig',['price' => $price,'user' => $user, 'product' => $product]);
    }

    /**
     * @Route("/transactionHistory", name="transactionhistory")
     * @Template("@vending_machine/employee/showTransactionHistory.html.twig")
     */

    public function transactionHistoryAction()
    {
        $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $transactionRepository = $em->getRepository('vending_machineBundle:Transactions');
        $transactions = $transactionRepository->findByUser($userId);

        return[
           'transactions' => $transactions
        ];
    }

    /**
     * @Route("/showHistory", name="showhistory")
     * @Template("@vending_machine/employee/showHistory.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function showHistoryAction()
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->findOneById($userId);

        return [
          'user' => $user
        ];
    }

    /**
     * @Route("/clearHistory", name="clearhistory")
     * @Security("has_role('ROLE_USER')")
     */

    public function clearHistoryAction()
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->findOneById($userId);

        $user->clearHistory();

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirect('/showHistory');
    }

    /**
     * @Route("/addRole/{userId}", name="addrole")
     */

    public function addRoleAction(Request $request, $userId)
    {
        $role = $request->request->get('role');

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->findOneById($userId);


        $user->addRole($role);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('showemployees');
    }

    /**
     * @Route("/removeRole/{userId}", name="removerole")
     */

    public function removeRoleAction(Request $request, $userId)
    {
        $role = $request->request->get('role');

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->findOneById($userId);


        $user->removeRole($role);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('showemployees');
    }
}
