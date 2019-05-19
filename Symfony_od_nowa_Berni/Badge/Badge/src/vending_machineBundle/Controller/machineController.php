<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\machine;


class machineController extends Controller
{

    /**
     *@Route("/newItem" , name="newItem")
     *@Template("@vending_machine/machine/addGoodsToMachine.html.twig")
     */

    public function newItemAction()
    {
        return [];
    }

    /**
     * @Route("/createItem" , name="createItem")
     */

    public function createItemAtcion(Request $request)
    {
        $newItem = $request->request->all();

        $itemToAdd = new machine();
        $itemToAdd->setProductName($newItem['product_name']);
        $itemToAdd->setPrice($newItem['price']);
        $itemToAdd->setStock($newItem['stock']);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($itemToAdd);
        $entityManager->flush();

        echo '<a href="http://127.0.0.1:8000/newItem">Dodaj więcej produktów</a><br><br>';
        echo '<a href="http://127.0.0.1:8000/availableGoods">Zobacz listę dostępnych produktów</a><br><br>';


        return new Response('Nowy produkt o ID= '.$itemToAdd->getId().' dodany! '.' Nazwa: ' .$itemToAdd->getProductName().', cena: '.$itemToAdd->getPrice().', ilość: '.$itemToAdd->getStock());
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
