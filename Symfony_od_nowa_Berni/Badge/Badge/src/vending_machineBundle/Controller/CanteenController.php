<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use vending_machineBundle\Entity\Canteen;
use vending_machineBundle\Entity\Vegan;
use vending_machineBundle\Form\CanteenType;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CanteenController
 * @package vending_machineBundle\Controller
 * @Route("canteen")
 */

class CanteenController extends Controller
{
    /**
     * Kontroler zajmujący się dodwaniem/edycją rodzajów serwowanych przez stołówkę dań
     */

    /**
     * @Route("/newService", name="newservice", methods="GET")
     * @Route("/newService" , name="createservice", methods="POST")
     * @Template("@vending_machine/canteen/newServiceForm.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function newService(Request $request)
    {
        if ($request->isMethod('GET')) {
            $newService = new Canteen();
            $form = $this->createForm(CanteenType::class, $newService, [
                'action' => $this->generateUrl('createservice')
            ]);
            return ['form' => $form->createView()];
        }

        $createService = new Canteen();
        $form = $this->createForm(CanteenType::class, $createService);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUser = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createUser);
            $em->flush();
        }
        return $this->redirectToRoute('showservice');
    }

    /**
     * @Route("/{id}/modifyService" , name="editservice")
     * @Template("@vending_machine/canteen/newServiceForm.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function modifyServiceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:Canteen');
        $service = $repository->find($id);

        $form = $this->createForm(CanteenType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('showservice');
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/listOfServices" , name="showservice")
     * @Template("@vending_machine/canteen/listOfServices.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function serviceListAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $canteenRepository = $entityManager->getRepository('vending_machineBundle:Canteen');

        $canteen = $canteenRepository->findAll();

        return[
            'meals' => $canteen,
        ];
    }

    /**
     * @Route("/weeklyMenu", name="weeklymenu")
     * @Template("@vending_machine/canteen/weeklyMenu.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function weeklyMenuAction()
    {
        $em = $this->getDoctrine()->getManager();
        $veganRepository = $em->getRepository('vending_machineBundle:Vegan');
        $vegan = $veganRepository->findAll();

        $meatRepository = $em->getRepository('vending_machineBundle:Meat');
        $meat = $meatRepository->findAll();

        return[
            'vegan' => $vegan,
            'meat' => $meat,
            'n' => 6
        ];
    }

    /**
     * @Route("/getDinner", name="getdinner", methods="GET")
     * @Template("@vending_machine/canteen/getDinner.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function getDinnerAction(Request $request)
    {
        if ($request->isMethod('GET')){
            return [];
        }
    }

    /**
     * @Route("/payForDinner/{meal}", name="payfordinner")
     * @Template("@vending_machine/employee/noEnoughMoney.html.twig")
     */

    public function payForDinnerAction(SessionInterface $session, $meal)
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->find($userId);

        $mealRepository = $em->getRepository('vending_machineBundle:Canteen');

        // Pobieram repo general Canteen
        $generalServiceRepository = $em->getRepository('vending_machineBundle:typesOfservices');
        $generalServiceCanteen = $generalServiceRepository->findOneById(2);
        $generalServiceDistributor = $generalServiceRepository->findOneById(1);

        $dateTime = new \DateTime('now');
        $day = $dateTime->format('N');
        $curentDay = Canteen::dateTranslate($day);

        $veganRepository = $em->getRepository('vending_machineBundle:Vegan');
        $whatsDay = $veganRepository->findOneByDay($curentDay);

        //W zależności od tego jaki obiadek wybrał użytkownik dokonujemy transakcji
        //Metoda buy dinner sprawdza jaką cenę ma zapłacić użytkownik na podstawie koloru badga

        if ($meal == 'Wegetariańskie'){
            $dish = $mealRepository->findOneByKindOfMeal($meal);
            $dish->buyDinner($user->getBadgeColor());
            $dish->upgradeNumberOfPurchased();
            $session->set('kindOfmeal', 'vegan');
        }

        else{
            $dish = $mealRepository->findOneByKindOfMeal($meal);
            $dish->buyDinner($user->getBadgeColor());
            $dish->upgradeNumberOfPurchased();
            $session->set('kindOfmeal', 'meat');
        }

        $dinnerPrice = Canteen::costOfDinner($user->getBadgeColor());

        //Linkuję pieniądze kantyny do tabeli typesOfservices
        $generalServiceCanteen->cashFromService($dinnerPrice);

        if ($user->getCash() < $dinnerPrice){

            return [
                'price' => $dinnerPrice,
                'user' => $user
            ];
        }

        $user->spentMoneyAdd($dinnerPrice);
        $user->buyFromMachine($dinnerPrice); //buyFromCanteen;)
        $user->checkDiscount();
        $operationHistory = "W ".$whatsDay->getDay(). " zjadłeś danie ".$meal. ", zapłaciłeś ".$dinnerPrice.", na koncie pozostało ".$user->getCash().".  ";
        $user->addToHistory($operationHistory);

        $distributorMoney = $generalServiceDistributor->getCash();
        $canteenMomey= $generalServiceCanteen->getCash();
        $total_cash = $distributorMoney + $canteenMomey;

        $generalServiceCanteen->setTotalCash($total_cash);
        $generalServiceDistributor->setTotalCash($total_cash);

        $em->persist($dish);
        $em->persist($generalServiceCanteen);
        $em->persist($generalServiceDistributor);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('dinnerratings');
    }

    /**
     * @Route("/rateTheDinner", name="dinnerratings")
     * @Template("@vending_machine/canteen/rateTheDinner.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function rateDinnerAction()
    {
        return[];
    }

    /**
     * @Route("/ratedDinner/{rate}", name="rateddinner")
     * @Template("@vending_machine/canteen/thanksForVote.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function ratedDinnerAction(SessionInterface $session, $rate)
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $user = $userRepository->find($userId);

        $kindOfMeal = $session->get('kindOfmeal');

        $dateTime = new \DateTime('now');
        $day = $dateTime->format('N');
        $curentDay = Canteen::dateTranslate($day);

        if ($kindOfMeal == 'vegan'){
            $veganRepository = $em->getRepository('vending_machineBundle:Vegan');
            $meal = $veganRepository->findOneByDay($curentDay);
        }
        else{
            $meatRepository = $em->getRepository('vending_machineBundle:Meat');
            $meal = $meatRepository->findOneByDay($curentDay);
        }

        //Dodaję osobę do głosujących
        $meal->addVoters();
        //Dodaję podaną liczbę punktów z oceny posiłku
        $meal->addRatePoint($rate);
        //Aktualizuję rating
        $meal->calculateRating();

        $em->persist($meal);
        $em->flush();
        $session->remove('kindOfmeal');

        return [
            'user' => $user
        ];
    }

}
