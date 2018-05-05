<?php

namespace ExpositionBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use ExpositionBundle\Entity\Exposition;
use ExpositionBundle\Form\ExpositionType;
use MuseumsBundle\Entity\History\Exposition_History;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;

class ExpositionManagementBundle extends Controller
{
    /**
     * @Route("/expositions", name="index_exposition_page")
     */
    public function indexAction()
    {
        return $this->render('@Exposition/list_expositions.html.twig');
    }

    /**
     * @Route("/api/expositions/paginate", name="paginate_expositions_page")
     */
    public function paginateAction(Request $request)
    {

        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;

        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];

        $expositions = $this->getDoctrine()->getRepository('ExpositionBundle:Exposition')->search(
            $filters, $start, $length
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('ExpositionBundle:Exposition')->search($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('ExpositionBundle:Exposition')->search(array(), 0, false))
        );

        foreach ($expositions as $exposition) {

            if($exposition->getEnabled()){
                $enabled = '<span class="label bg-success-400">Enabled</span>';
            }
            else{
                $enabled = '<span class="label bg-danger-400">Disabled</span>';
            }

            $images = array();
            foreach ($exposition->getImages()->getValues() as $img){
                $images[] = array(
                    'id' => $img->getId(),
                    'image' => $img->getImage(),
                    'updated_at' => $img->getUpdatedat(),
                );
            }

            $output['data'][] = [
                'id' => $exposition->getId(),
                'name' => $exposition->getName(),
                'description' => $exposition->getDescription(),
                'start' => $exposition->getStart()->format('Y-m-d'),
                'finish' => $exposition->getFinish()->format('Y-m-d'),
                'museum' => $exposition->getHostingMuseum()->getName(   ),
                'enabled' => $enabled,
                'links' => '
                    <ul class="icons-list">
                        <li><a href="/expositions/enable/'.$exposition->getId().'" data-popup="tooltip" title="Toggle"><i class="icon-checkmark"></i></a></li>
                        <li><a href="/expositions/edit/'.$exposition->getId().'" data-popup="tooltip" title="Edit"><i class="icon-pencil3"></i></a></li>
                        <li><a href="/expositions/delete/'.$exposition->getId().'" data-popup="tooltip" title="Delete" onclick="return confirm(\'Are you sure you want to Delete this Exposition?\')"><i class="icon-trash"></i></a></li>
                    </ul>
                ',
                'images' => $images,
            ];
        }
        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * @Route("/expositions/add", name="add_exposition_page")
     */
    public function addAction(Request $request)
    {
        $exposition = new Exposition();

        $form = $this->get('form.factory')->create(ExpositionType::class, $exposition);
        $exposition->setEnabled(false);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($exposition);

            $exposition_history = new Exposition_History();
            $exposition_history->setDate(new \DateTime("now"));
            $exposition_history->setAction("Insert");
            foreach ($exposition->getImages() as $img){
                $img->setExposition($exposition);
            }

            $em->flush();

            $exposition_history->setExpositionId($exposition->getId());
            $em->persist($exposition_history);
            $em->flush();

            return $this->redirectToRoute('index_exposition_page');
        }

        return $this->render('@Exposition/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/expositions/edit/{id}", name="edit_exposition_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $exposition = $em->getRepository(Exposition::class)->find($id);
        if(null === $exposition)
        {
            throw new NotFoundHttpException("Exposition with id: ".$id." doesn't exist");
        }

        $originalImages = new ArrayCollection();

        // Create an ArrayCollection of the current Image objects in the database
        foreach ($exposition->getImages() as $image)
        {
            $originalImages->add($image);
        }

        $form = $this->createForm(ExpositionType::class, $exposition);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            foreach ($originalImages as $image)
            {
                if(false === $exposition->getImages()->contains($image))
                {
                    // remove image
                    $em->remove($image);
                }
            }

            $exposition_history = new Exposition_History();
            $exposition_history->setDate(new \DateTime("now"));
            $exposition_history->setAction("Edit");
            $exposition_history->setExpositionId($exposition->getId());
            $em->persist($exposition_history);

            $em->flush();
            return $this->redirectToRoute('index_exposition_page');
        }

        return $this->render('@Exposition/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/expositions/delete/{id}", name="delete_expostion_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $exposition = $em->getRepository(Exposition::class)->find($id);
        if(null === $exposition)
        {
            throw new NotFoundHttpException('Exposition with id: '.$id." doesn't exist");
        }

        $exposition_history = new Exposition_History();
        $exposition_history->setDate(new \DateTime("now"));
        $exposition_history->setAction("Delete");
        $exposition_history->setExpositionid($exposition->getId());
        $em->persist($exposition_history);

        $em->remove($exposition);
        $em->flush();

        return $this->redirectToRoute('index_exposition_page');
    }

    /**
     * @Route("/expositions/enable/{id}", name="enable_exposition_page")
     */
    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $exposition = $em->getRepository(Exposition::class)->find($id);
        if(null === $exposition)
        {
            throw new NotFoundHttpException("Exposition with id: ".$id." doesn't exist");
        }

        if($exposition->getEnabled())
        {
            $exposition->setEnabled(false);
        }
        else
        {
            $exposition->setEnabled(true);
        }
        $em->flush();

        return $this->redirectToRoute('index_exposition_page');
    }
}
