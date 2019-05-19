<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\machine;
use vending_machineBundle\Form\machineType;


class machineController extends Controller
{

    /**
     *@Route("/newItem" , name="newItem", methods="GET")
     *@Route("/newItem" , name="createItem", methods="POST")
     *@Template("@vending_machine/machine/addGoodsToMachine.html.twig")
     */

    public function newItemAction(Request $request)
    {
        if ($request->isMethod('GET')) {
            $item = new machine();
            $form = $this->createForm(machineType::class, $item, [
                'action' => $this->generateUrl('createItem')
            ]);
            return ['form' => $form->createView()];
        }

        $createItem = new machine();
        $form = $this->createForm(machineType::class, $createItem);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUser = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createUser);
            $em->flush();
        }
        return new Response('Produkt dodany');
    }

    /**
     * @Route("/{id}/modifyItem" , name="editItem")
     * @Template("@vending_machine/machine/addGoodsToMachine.html.twig")
     */

    public function modifyUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:machine');
        $item = $repository->find($id);

        if (!$item) {
            return new Response('Przedmiot o podanym ID nie istnieje');
        }

        $form = $this->createForm(machineType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            return new Response('Przedmiot pomyślnie zmodyfikowany');
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/availableGoods" , name="stockInMachine")
     * @Template("@vending_machine/machine/availableGoods.html.twig")
     */

    public function availableGoodsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('vending_machineBundle:machine');

        $products = $repository->findAll();

        return[
            'products' => $products
        ];
    }
}
