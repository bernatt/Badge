<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\employee;
use vending_machineBundle\Form\employeeType;


class employeeUserController extends Controller
{
    /**
     * @Route("/newEmployee" , name="newemployee", methods="GET")
     * @Route("/newEmployee" , name="createmployee", methods="POST")
     * @Template("@vending_machine/employee/addForm.html.twig")
     */

    public function newItemAction(Request $request)
    {
        if ($request->isMethod('GET')) {
            $employee = new employee();
            $form = $this->createForm(employeeType::class, $employee, [
                'action' => $this->generateUrl('createmployee')
            ]);
            return ['form' => $form->createView()];
        }

        $createEmployee = new employee();
        $form = $this->createForm(employeeType::class, $createEmployee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createUser = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($createUser);
            $em->flush();
        }
        return $this->redirect('/showEmployees');
    }

    /**
     * @Route("/{id}/modifyEmployee" , name="editemployee")
     * @Template("@vending_machine/employee/addForm.html.twig")
     */

    public function modifyUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('vending_machineBundle:employee');
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

    public function availableGoodsAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository('vending_machineBundle:employee');

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
        $repository = $em->getRepository('vending_machineBundle:employee');
        $employee = $repository->find($id);

        $employee->deposit($request->request->get('deposit'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($employee);
        $em->flush();

        return $this->redirect('/showEmployees');
    }
}
