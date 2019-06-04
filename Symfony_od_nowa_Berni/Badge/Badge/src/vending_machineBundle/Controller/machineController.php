<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\machine;
use vending_machineBundle\Form\machineType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class machineController extends Controller
{

    /**
     * @Route("/newItem" , name="newItem", methods="GET")
     * @Route("/newItem" , name="createItem", methods="POST")
     * @Template("@vending_machine/machine/addGoodsToMachine.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
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
        return $this->redirect('/availableGoods');
    }

    /**
     * @Route("/{id}/modifyItem" , name="editItem")
     * @Template("@vending_machine/machine/addGoodsToMachine.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function modifyItemAction(Request $request, $id)
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

            return $this->redirect('/availableGoods');
        }
        return['form' => $form->createView()];
    }

    /**
     * @Route("/availableGoods" , name="stockInMachine")
     * @Template("@vending_machine/machine/availableGoods.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function availableGoodsAdminAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $machineRepository = $entityManager->getRepository('vending_machineBundle:machine');
        $userRepository = $entityManager->getRepository('vending_machineBundle:User');

        $loggedUser = $this->getUser()->getId();
        $user = $userRepository->findOneById($loggedUser);

        $products = $machineRepository->findAll();

        return[
            'products' => $products,
            'user' => $user
        ];
    }

    /**
     * @Route("/availableGoodsUser" , name="stockInMachineUser")
     * @Template("@vending_machine/machine/availableGoodsForUser.html.twig")
     * @Security("has_role('ROLE_USER')")
     */

    public function availableGoodsUserAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $machineRepository = $entityManager->getRepository('vending_machineBundle:machine');
        $userRepository = $entityManager->getRepository('vending_machineBundle:User');

        $loggedUser = $this->getUser()->getId();
        $user = $userRepository->findOneById($loggedUser);

        $products = $machineRepository->findAll();

        return[
            'products' => $products,
            'user' => $user
        ];
    }
}

