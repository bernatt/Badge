<?php

namespace vending_machineBundle\Controller;


use vending_machineBundle\Entity\Transactions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Transaction controller.
 *
 * @Route("transactions")
 */
class TransactionsController extends Controller
{
    /**
     * Lists all transaction entities.
     *
     * @Route("/", name="transactions_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $transactions = $em->getRepository('vending_machineBundle:Transactions')->findAll();

        return $this->render('transactions/index.html.twig', array(
            'transactions' => $transactions,
        ));
    }

    /**
     * Creates a new transaction entity.
     *
     * @Route("/new", name="transactions_new")
     *
     */
    public function newAction(Request $request)
    {
        $transaction = new Transactions();
        $form = $this->createForm('vending_machineBundle\Form\TransactionsType', $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $em->flush();

            return $this->redirectToRoute('transactions_show', array('id' => $transaction->getId()));
        }

        return $this->render('transactions/new.html.twig', array(
            'transaction' => $transaction,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a transaction entity.
     *
     * @Route("/view/{id}", name="transactions_show")
     *
     */
    public function showAction(Transactions $transaction)
    {
        $deleteForm = $this->createDeleteForm($transaction);

        return $this->render('transactions/show.html.twig', array(
            'transaction' => $transaction,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing transaction entity.
     *
     * @Route("/{id}/edit", name="transactions_edit")
     *
     */
    public function editAction(Request $request, Transactions $transaction)
    {
        $deleteForm = $this->createDeleteForm($transaction);
        $editForm = $this->createForm('vending_machineBundle\Form\TransactionsType', $transaction);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('transactions_edit', array('id' => $transaction->getId()));
        }

        return $this->render('transactions/edit.html.twig', array(
            'transaction' => $transaction,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/robto/", name="x")
     */
    public function xxxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('vending_machineBundle:User');
        $machineRepository = $em->getRepository('vending_machineBundle:machine');
        $user= $userRepository->findOneById(1);
        $machine= $machineRepository->findAll();

        $transaction = new Transactions();
        $transaction->setUser($user);
        $transaction->setMoneyLeft(15);
        $transaction->setPrice(10);
        $transaction->setQuantity(1);
        $transaction->setProductName('sok');
        $transaction->setMachine($machine[0]);


        $em->persist($transaction);
        $em->flush();

        return new Response('ok pykło');

    }

    /**
     * Deletes a transaction entity.
     *
     * @Route("/del/{id}", name="transactions_delete")
     *
     */
    public function deleteAction(Request $request, Transactions $transaction)
    {
        $form = $this->createDeleteForm($transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($transaction);
            $em->flush();
        }

        return $this->redirectToRoute('transactions_index');
    }

    /**
     * Creates a form to delete a transaction entity.
     *
     * @param Transactions $transaction The transaction entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Transactions $transaction)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('transactions_delete', array('id' => $transaction->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
