<?php

namespace MuseumsBundle\Controller;

use MuseumsBundle\Entity\Critere;
use MuseumsBundle\Entity\History\Critere_History;
use MuseumsBundle\Form\CritereType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CritereManagementController extends Controller
{
    /**
     * @Route("/criteres", name="index_critere_page")
     */
    public function indexAction()
    {
        $listCriteres = $this->getDoctrine()
            ->getManager()
            ->getRepository(Critere::class)
            ->findAll()
        ;

        return $this->render('@Museums/Critere/list.html.twig', array(
            'listCriteres' => $listCriteres
        ));
    }

    /**
     * @Route("/criteres/add", name="add_critere_page")
     */
    public function addAction(Request $request)
    {
        $critere = new Critere();
        $form   = $this->get('form.factory')->create(CritereType::class, $critere);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($critere);

            $critere_history = new Critere_History();
            $critere_history->setDate(new \DateTime("now"));
            $critere_history->setAction("Insert");

            $em->flush();

            $critere_history->setCritereId($critere->getId());
            $em->persist($critere_history);
            $em->flush();

            return $this->redirectToRoute('index_critere_page');
        }

        return $this->render('@Museums/Critere/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/criteres/edit/{id}", name="edit_critere_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $critere = $em->getRepository(Critere::class)->find($id);
        if(null === $critere)
        {
            throw new NotFoundHttpException("Critere with id: ".$id." doesn't exist");
        }
        $form = $this->createForm(CritereType::class, $critere);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $critere_history = new Critere_History();
            $critere_history->setDate(new \DateTime("now"));
            $critere_history->setAction("Edit");
            $critere_history->setCritereId($critere->getId());
            $em->persist($critere_history);

            $em->flush();
            return $this->redirectToRoute('index_critere_page');
        }

        return $this->render('@Museums/Critere/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/criteres/delete/{id}", name="delete_critere_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $critere = $em->getRepository(Critere::class)->find($id);
        if(null === $critere)
        {
            throw new NotFoundHttpException('Critere with id: '.$id." doesn't exist");
        }

        $critere_history = new Critere_History();
        $critere_history->setDate(new \DateTime("now"));
        $critere_history->setAction("Delete");
        $critere_history->setCritereId($critere->getId());
        $em->persist($critere_history);

        $em->remove($critere);
        $em->flush();

        return $this->redirectToRoute('index_critere_page');
    }
}
