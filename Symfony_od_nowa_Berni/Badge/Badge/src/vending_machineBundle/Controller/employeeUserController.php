<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
//use vending_machineBundle\Entity\employee;
use vending_machineBundle\Form\employeeType;



class employeeUserController extends Controller
{
//    /**
//     * @Route("/newEmployee" , name="newemployee", methods="GET")
//     * @Route("/newEmployee" , name="createmployee", methods="POST")
//     * @Template("@vending_machine/employee/addForm.html.twig")
//     */
//
//    public function newEmployeeAction(Request $request)
//    {
//        if ($request->isMethod('GET')) {
//            $employee = new employee();
//            $form = $this->createForm(employeeType::class, $employee, [
//                'action' => $this->generateUrl('createmployee')
//            ]);
//            return ['form' => $form->createView()];
//        }
//
//        $createEmployee = new employee();
//        $form = $this->createForm(employeeType::class, $createEmployee);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $createUser = $form->getData();
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($createUser);
//            $em->flush();
//        }
//        return $this->redirect('/showEmployees');
//    }
//
    /**
     * @Route("/{id}/modifyEmployee" , name="editemployee")
     * @Template("@vending_machine/employee/modifyEmployee.html.twig")
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
     */

    public function listOfEmployeeAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('vending_machineBundle:User');

        $users = $repository->findAll();

        return[
            'users' => $users
        ];
    }

    /**
     * @Route("/payment/{id}", name="deposit", methods="POST")
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

        return $this->redirect('/showEmployees');
    }

    /**
     * @Route("/buyFromMachine/{user_id}/{product_id}", name="buyfrommachine")
     *
     * Metoda pozwalająca użytkownikowi kupić produkt w automacie.
     * Korzysta z dwóch encji ( machine i User ),
     * Wprowadzona została walidacja zarówno ilości produktów w automacie jak i ilośći środków na koncie użytkownika.
     * Dodana została arhiwizacja zakupów w historii encji User.
     */

    public function buyFromMachineAction(Request $request, $user_id, $product_id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->find($user_id);

        $userRepository = $em->getRepository('vending_machineBundle:machine');
        $product = $userRepository->find($product_id);


        $quantity = $request->request->get('quantity');

        if ($quantity > $product->getStock()){
            return new Response('Brak wystarczającej ilośći produktów');
        }
        $product->stockCorrection($quantity);
        $price = $product->getPrice() * $quantity;

        if ($user->getCash() < $price){
            return new Response('Nie masz wystarczająco dużo gotówki misiu ;/');
        }
        $user->buyFromMachine($price);

        $operationHistory = "Kupiłeś ".$quantity. " ".$product->getProductName().". Zapłaciłeś ".$price.", na koncie pozostało Ci ".$user->getCash().", ";
        $user->addToHistory($operationHistory);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($product);

        $em->flush();

        return $this->redirect('/showEmployees');

    }

    /**
     * @Route("/showHistory", name="showhistory")
     * @Template("@vending_machine/employee/showHistory.html.twig")
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

}
