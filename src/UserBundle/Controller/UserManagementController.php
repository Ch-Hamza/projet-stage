<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\HistoryEntry;
use UserBundle\Entity\User;
use UserBundle\Form\EditUserType;
use UserBundle\Form\UserType;

class UserManagementController extends Controller
{
    /**
     * @Route("/", name="index_user_page")
     */
    public function indexAction()
    {
        $listUsers = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findAll()
        ;

        return $this->render('UserBundle::list.html.twig', array(
            'listUsers' => $listUsers,
        ));
    }

    /**
     * @Route("/view/{id}", name="view_user_page")
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if(null === $user)
        {
            throw new NotFoundHttpException("User with id: ".$id." doesn't exist");
        }

        $listHist = $em
            ->getRepository(HistoryEntry::class)
            ->findBy(array('user' => $user))
        ;

        return $this->render('UserBundle::view.html.twig', array(
            'user' => $user,
            'listHist' => $listHist
        ));
    }

   /*
    public function addAction(Request $request)
    {
        /*$user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('index_user_page');
        }
        return $this->render('UserBundle::add.html.twig', array(
            'form' => $form->createView(),
        ));*

        $userManger = $this->get('fos_user.user_manager');
        $user = $userManger->createUser();
        $user->setEnabled(true);
        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            $encoded_pass = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encoded_pass);
            $userManger->updateUser($user);
            return $this->redirectToRoute('index_user_page');
        }

        return $this->render('UserBundle::add.html.twig', array(
            'form' => $form->createView(),
        ));
    }*/

    /**
     * @Route("/edit/{id}", name="edit_user_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if(null === $user)
        {
            throw new NotFoundHttpException("User with id: ".$id." doesn't exist");
        }
        $userManger = $this->get('fos_user.user_manager');
        $form = $this->createForm(EditUserType::class, $user);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $userManger->updateUser($user);
            return $this->redirectToRoute('index_user_page');
        }

        return $this->render('UserBundle::edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete_user_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if(null === $user)
        {
            throw new NotFoundHttpException('User with id: '.$id." doesn't exist");
        }
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('index_user_page');
    }

}
