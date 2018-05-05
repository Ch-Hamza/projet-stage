<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\EditUserType;
use UserBundle\Form\UserType;

class UserManagementController extends Controller
{
    /**
     * @Route("/admins", name="index_admins_page")
     */
    public function indexAction()
    {
        $listUsers = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findByRole('ROLE_ADMIN')
        ;

        return $this->render('UserBundle::list.html.twig', array(
            'listUsers' => $listUsers,
        ));
    }

    /**
     * @Route("/operators", name="index_operators_page")
     */
    public function operatorsAction()
    {
        $listUsers = $this->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findByRole('ROLE_OPERATOR')
        ;

        return $this->render('@User/operator/operators_list.html.twig', array(
            'listUsers' => $listUsers,
        ));
    }

    /**
     * @Route("/admins/add", name="add_user_page")
     */
    public function addAction(Request $request)
    {
        $userManger = $this->get('fos_user.user_manager');
        $user = $userManger->createUser();
        $user->setEnabled(true);
        $user->removeRole("ROLE_OPERATOR");
        $user->addRole('ROLE_ADMIN');
        $user->setUpdatedAt(new \DateTime('now'));

        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $encoder_service = $this->get('security.encoder_factory');
            $encoder = $encoder_service->getEncoder($user);
            $encoded_pass = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($encoded_pass);

            $userManger->updateUser($user);
            return $this->redirectToRoute('index_admins_page');
        }

        return $this->render('UserBundle::add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/admins/edit/{id}", name="edit_user_page")
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
            return $this->redirectToRoute('index_page');
        }

        return $this->render('UserBundle::edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/admins/delete/{id}", name="delete_user_page")
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

        return $this->redirectToRoute('index_page');
    }

    /**
     * @Route("/admins/enable/{id}", name="enable_admin_page")
     */
    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if(null === $user)
        {
            throw new NotFoundHttpException("User with id: ".$id." doesn't exist");
        }

        if($user->isEnabled())
        {
            $user->setEnabled(false);
        }
        else
        {
            $user->setEnabled(true);
        }
        $em->flush();

        return $this->redirectToRoute('index_admins_page');
    }

    /**
     * @Route("/operators/enable/{id}", name="enable_operator_page")
     */
    public function toggleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        if(null === $user)
        {
            throw new NotFoundHttpException("User with id: ".$id." doesn't exist");
        }

        if($user->isEnabled())
        {
            $user->setEnabled(false);
        }
        else
        {
            $user->setEnabled(true);
        }
        $em->flush();

        return $this->redirectToRoute('index_operators_page');
    }
}
