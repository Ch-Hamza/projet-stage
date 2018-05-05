<?php

namespace AdvertisementBundle\Controller;

use AdvertisementBundle\Entity\Advertisement;
use AdvertisementBundle\Form\AdvertisementType;
use MuseumsBundle\Entity\History\Advertisement_History;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdManagementController extends Controller
{
    /**
     * @Route("/list", name="index_ads_page")
     */
    public function indexAction()
    {
        $listAds = $this->getDoctrine()
            ->getManager()
            ->getRepository(Advertisement::class)
            ->findAll()
        ;

        return $this->render('@Advertisement/list.html.twig', array(
            'listAds' => $listAds
        ));
    }

    /**
     * @Route("/my-advertisements/{id}", name="browse_ads_page")
     */
    public function browseAction($id)
    {
        $listAds = $this->getDoctrine()
            ->getManager()
            ->getRepository(Advertisement::class)
            ->findBy(array('user' => $id))
        ;

        return $this->render('@Advertisement/list.html.twig', array(
            'listAds' => $listAds
        ));
    }

    /**
     * @Route("/add", name="add_ad_page")
     */
    public function addAction(Request $request)
    {
        $ad = new Advertisement();
        $form   = $this->get('form.factory')->create(AdvertisementType::class, $ad);
        $ad->setEnabled(false);
        $ad->setUser($this->getUser());
        $ad->setAddedAt(new \DateTime('now'));

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($ad);

            $ad_history = new Advertisement_History();
            $ad_history->setDate(new \DateTime("now"));
            $ad_history->setAction("Insert");

            $em->flush();

            $ad_history->setAdvertisementId($ad->getId());
            $em->persist($ad_history);
            $em->flush();

            return $this->redirectToRoute('browse_ads_page', array('id' => $this->getUser()->getId()));
        }

        return $this->render('@Advertisement/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit_ad_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository(Advertisement::class)->find($id);
        if(null === $ad)
        {
            throw new NotFoundHttpException("Advertisement with id: ".$id." doesn't exist");
        }
        $form = $this->createForm(AdvertisementType::class, $ad);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $ad_history = new Advertisement_History();
            $ad_history->setDate(new \DateTime("now"));
            $ad_history->setAction("Edit");
            $ad_history->setAdvertisementId($ad->getId());
            $em->persist($ad_history);
            $em->flush();
            return $this->redirectToRoute('browse_ads_page', array('id' => $this->getUser()->getId()));
        }

        return $this->render('@Advertisement/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete_ad_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository(Advertisement::class)->find($id);
        if(null === $ad)
        {
            throw new NotFoundHttpException('Advertisement with id: '.$id." doesn't exist");
        }
        $ad_history = new Advertisement_History();
        $ad_history->setDate(new \DateTime("now"));
        $ad_history->setAction("Delete");
        $ad_history->setAdvertisementId($ad->getId());
        $em->persist($ad_history);
        $em->remove($ad);
        $em->flush();

        return $this->redirectToRoute('browse_ads_page', array('id' => $this->getUser()->getId()));
    }

    /**
     * @Route("/enable/{id}", name="enable_ad_page")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function enableAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ad = $em->getRepository(Advertisement::class)->find($id);
        if(null === $ad)
        {
            throw new NotFoundHttpException("Advertisement with id: ".$id." doesn't exist");
        }

        if($ad->getEnabled())
        {
            $ad->setEnabled(false);
            $ad->setEnabledAt(new \DateTime('now'));
        }
        else
        {
            $ad->setEnabled(true);
        }
        $em->flush();

        return $this->redirectToRoute('index_ads_page');
    }
}
