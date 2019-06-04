<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\Vegan;
use vending_machineBundle\Form\VeganType;

/**
 * Class VeganController
 * @package vending_machineBundle\Controller
 * @Route("vegan")
 */

class VeganController extends Controller
{
    /**
     * Kontroler zajmujący się dodwaniem/edycją dań wegetarjańskich
     */

    /**
     * @Route("/newVegan", name="newvegan", methods="GET")
     * @Route("/newVegan" , name="createvegan", methods="POST")
     * @Template("@vending_machine/canteen/vegan/newVeganForm.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function newService(Request $request)
    {
        if ($request->isMethod('GET')) {
            $newVegan = new Vegan();
            $form = $this->createForm(VeganType::class, $newVegan, [
                'action' => $this->generateUrl('createvegan')
            ]);
            return ['form' => $form->createView()];
        }

        $createVegan = new Vegan();
        $form = $this->createForm(VeganType::class, $createVegan);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createVegan = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createVegan);
            $em->flush();
        }
        return $this->redirectToRoute('showvegan');
    }

    /**
     * @Route("/{id}/modifyVegan" , name="editvegan")
     * @Template("@vending_machine/canteen/vegan/newVeganForm.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function modifyItemAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:Vegan');
        $vegan = $repository->find($id);

        $form = $this->createForm(VeganType::class, $vegan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vegan = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($vegan);
            $em->flush();

            return $this->redirectToRoute('showvegan');
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/listVegan" , name="showvegan")
     * @Template("@vending_machine/canteen/vegan/listVegan.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function availableGoodsAdminAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $veganRepository = $entityManager->getRepository('vending_machineBundle:Vegan');

        $vegan = $veganRepository->findAll();

        return[
            'vegan' => $vegan,
        ];
    }
}
