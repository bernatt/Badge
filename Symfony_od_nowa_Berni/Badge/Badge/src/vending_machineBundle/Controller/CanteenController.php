<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use vending_machineBundle\Entity\Canteen;
use vending_machineBundle\Form\CanteenType;

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

    public function getDinner(Request $request)
    {
        if ($request->isMethod('GET')){
            return [];
        }
    }

}
