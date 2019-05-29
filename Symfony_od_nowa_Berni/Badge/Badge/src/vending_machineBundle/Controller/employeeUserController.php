<?php

namespace vending_machineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use vending_machineBundle\Entity\employee;
use vending_machineBundle\Form\employeeType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class employeeUserController extends Controller
{
    /**
     * @Route("/newEmployee" , name="newemployee", methods="GET")
     * @Route("/newEmployee" , name="createmployee", methods="POST")
     * @Template("@vending_machine/employee/addForm.html.twig")
     */

    public function newEmployeeAction(Request $request)
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
     * @Template("@vending_machine/employee/modifyEmployee.html.twig")
     */

    public function modifyEmployeeAction(Request $request, $id)
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

//    /**
//     * @Route("/connectEmployee", name="connectempget",methods="GET" )
//     * @Route("/connectEmployee", name="connectemp",methods="POST" )
//     * @Template("@vending_machine/employee/logInEmployee.html.twig")
//     */
//
//    public function connectEmployeeAction(Request $request)
//    {
//        if ($request->isMethod('GET')) {
//            return [];
//        }
//        $em = $this->getDoctrine()->getManager();
//        $repository = $em->getRepository('vending_machineBundle:employee');
//        $badgeNrByRequest = $request->request->get('badge_nr');
//
//        // Retrieve the security encoder of symfony
//        $factory = $this->get('security.encoder_factory');
//
//        /// Start retrieve user
//        // Let's retrieve the user by its username:
//        $employeeName = $repository->findOneByName($request->request->get('name'));
//
//        if(!$employeeName){
//            return new Response('Nie ma takiego użytkownika');
//        }
//
//        // Start verification
//        $encoder = $factory->getEncoder($employeeName);
//
//        if (!$encoder->isPasswordValid($employeeName->getBadgeNr(), $badgeNrByRequest)){
//            return new Response('Hasło nie jest poprawne');
//        }
//
//        //Handle getting or creating the user entity likely with a posted form
//        // The third parameter "main" can change according to the name of your firewall in security.yml
//        $token = new UsernamePasswordToken($employeeName, null, 'main');
//        $this->get('security.token_storage')->setToken($employeeName);
//
//        // Fire the login event manually
//        $event = new InteractiveLoginEvent($request, $token);
//        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
//
//        return new Response('Pracownik zalogowany');
//    }
}
