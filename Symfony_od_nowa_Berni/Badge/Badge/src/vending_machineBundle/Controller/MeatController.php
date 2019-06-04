<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\Meat;
use vending_machineBundle\Form\MeatType;


/**
 * Class MeatController
 * @package vending_machineBundle\Controller
 * @Route("meat")
 */

class MeatController extends Controller
{
    /**
     * Kontroler zajmujący się dodwaniem/edycją dań mięsnych
     */

    /**
     * @Route("/newMeat", name="newmeat", methods="GET")
     * @Route("/newMeat" , name="createmeat", methods="POST")
     * @Template("@vending_machine/canteen/meat/newMeatForm.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function newMeatAction(Request $request)
    {
        if ($request->isMethod('GET')) {
            $newMeat = new Meat();
            $form = $this->createForm(MeatType::class, $newMeat, [
                'action' => $this->generateUrl('createmeat')
            ]);
            return ['form' => $form->createView()];
        }

        $createMeat = new Meat();
        $form = $this->createForm(MeatType::class, $createMeat);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createMeat = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createMeat);
            $em->flush();
        }
        return $this->redirectToRoute('showmeat');
    }

    /**
     * @Route("/{id}/modifyMeat" , name="editmeat")
     * @Template("@vending_machine/canteen/meat/newMeatForm.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function modifyMeatAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:Meat');
        $meat = $repository->find($id);

        $form = $this->createForm(MeatType::class, $meat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meat = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($meat);
            $em->flush();

            return $this->redirectToRoute('showmeat');
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/listMeat" , name="showmeat")
     * @Template("@vending_machine/canteen/meat/listMeat.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function meatListAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $meatRepository = $entityManager->getRepository('vending_machineBundle:Meat');

        $meat = $meatRepository->findAll();

        return[
            'meat' => $meat,
        ];
    }
}
