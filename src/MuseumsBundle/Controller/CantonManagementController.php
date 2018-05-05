<?php

namespace MuseumsBundle\Controller;

use MuseumsBundle\Entity\Canton;
use MuseumsBundle\Entity\History\Canton_History;
use MuseumsBundle\Form\CantonType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CantonManagementController extends Controller
{
    /**
     * @Route("/canton", name="index_canton_page")
     */
    public function indexAction()
    {
        $listCanton = $this->getDoctrine()
            ->getManager()
            ->getRepository(Canton::class)
            ->findAll()
        ;

        return $this->render('@Museums/Canton/list.html.twig', array(
            'listCanton' => $listCanton
        ));
    }

    /**
     * @Route("/canton/add", name="add_canton_page")
     */
    public function addAction(Request $request)
    {
        $canton = new Canton();
        $form   = $this->get('form.factory')->create(CantonType::class, $canton);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($canton);

            $canton_history = new Canton_History();
            $canton_history->setDate(new \DateTime("now"));
            $canton_history->setAction("Insert");

            $em->flush();

            $canton_history->setCantonId($canton->getId());
            $em->persist($canton_history);
            $em->flush();

            return $this->redirectToRoute('index_canton_page');
        }

        return $this->render('@Museums/Canton/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/canton/edit/{id}", name="edit_canton_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $canton = $em->getRepository(Canton::class)->find($id);
        if(null === $canton)
        {
            throw new NotFoundHttpException("Canton with id: ".$id." doesn't exist");
        }
        $form = $this->createForm(CantonType::class, $canton);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $canton_history = new Canton_History();
            $canton_history->setDate(new \DateTime("now"));
            $canton_history->setAction("Edit");
            $canton_history->setCantonId($canton->getId());
            $em->persist($canton_history);

            $em->flush();
            return $this->redirectToRoute('index_canton_page');
        }

        return $this->render('@Museums/Canton/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/canton/delete/{id}", name="delete_canton_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $canton = $em->getRepository(Canton::class)->find($id);
        if(null === $canton)
        {
            throw new NotFoundHttpException('Canton with id: '.$id." doesn't exist");
        }

        $canton_history = new Canton_History();
        $canton_history->setDate(new \DateTime("now"));
        $canton_history->setAction("Delete");
        $canton_history->setCantonId($canton->getId());
        $em->persist($canton_history);

        $em->remove($canton);
        $em->flush();

        return $this->redirectToRoute('index_canton_page');
    }
}
