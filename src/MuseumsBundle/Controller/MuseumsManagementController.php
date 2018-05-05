<?php

namespace MuseumsBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MuseumsBundle\Entity\History\Museum_History;
use MuseumsBundle\Entity\Museum;
use MuseumsBundle\Form\MuseumType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Tests\Fixtures\ToString;

class MuseumsManagementController extends Controller
{
    /**
     * @Route("/museums", name="index_museums_page")
     */
    public function indexAction()
    {
        return $this->render('@Museums/list_museums.html.twig');
    }

    /**
     * @Route("/api/paginate", name="paginate_page")
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

        $museums = $this->getDoctrine()->getRepository('MuseumsBundle:Museum')->search(
            $filters, $start, $length
        );

        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('MuseumsBundle:Museum')->search($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('MuseumsBundle:Museum')->search(array(), 0, false))
        );

        foreach ($museums as $museum) {

            if($museum->getEnabled()){
                $enabled = '<span class="label bg-success-400">Enabled</span>';
            }
            else{
                $enabled = '<span class="label bg-danger-400">Disabled</span>';
            }

            $images = array();
            foreach ($museum->getImages()->getValues() as $img){
                $images[] = array(
                    'id' => $img->getId(),
                    'image' => $img->getImage(),
                    'updated_at' => $img->getUpdatedat(),
                );
            }

            $categories = array();
            foreach ($museum->getCategories()->getValues() as $category){
                $categories[] = array(
                    'id' => $category->getId(),
                    'title_fr' => $category->getTitleFr(),
                    'title_en' => $category->getTitleEn(),
                    'title_it' => $category->getTitleIt(),
                    'title_de' => $category->getTitleDe(),
                    'image' => $category->getImage(),
                    'updated_at' => $category->getUpdatedat(),
                );
            }

            $criteres = array();
            foreach ($museum->getCriteres()->getValues() as $critere){
                $criteres[] = array(
                    'id' => $critere->getId(),
                    'title_fr' => $critere->getTitleFr(),
                    'title_en' => $critere->getTitleEn(),
                    'title_it' => $critere->getTitleIt(),
                    'title_de' => $critere->getTitleDe(),
                    'image' => $critere->getImage(),
                    'updated_at' => $critere->getUpdatedat(),
                );
            }

            $location = $museum->getLocation();
            $location_array = array(
                'id' => $location->getId(),
                'longitude' => $location->getLongitude(),
                'lattitude' => $location->getLattitude(),
            );


            $output['data'][] = [
                'id' => $museum->getId(),
                'name' => $museum->getName(),
                'schedule' => $museum->getHoraire(),
                'price' => $museum->getPrice(),
                'rue' => $museum->getRue(),
                'code_postal' => $museum->getCodepostal(),
                'place' => $museum->getPlace(),
                'canton' => $museum->getCanton()->getTitle(),
                'enabled' => $enabled,
                'links' => '
                    <ul class="icons-list">
                        <li><a href="/museums/enable/'.$museum->getId().'" data-popup="tooltip" title="Toggle"><i class="icon-checkmark"></i></a></li>
                        <li><a href="/museums/edit/'.$museum->getId().'" data-popup="tooltip" title="Edit"><i class="icon-pencil3"></i></a></li>
                        <li><a href="/museums/delete/'.$museum->getId().'" data-popup="tooltip" title="Delete" onclick="return confirm(\'Are you sure you want to Delete this Museum?\')"><i class="icon-trash"></i></a></li>
                    </ul>
                ',
                'description_fr' => $museum->getDescriptionFr(),
                'description_en' => $museum->getDescriptionEn(),
                'description_it' => $museum->getDescriptionIt(),
                'description_de' => $museum->getDescriptionDe(),
                'directions' => $museum->getDirections(),
                'phone' => $museum->getPhone(),
                'fax' => $museum->getFax(),
                'email' => $museum->getEmail(),
                'website' => $museum->getWebsite(),
                'images' => $images,
                'categories' => $categories,
                'criteres' => $criteres,
                'location' => $location_array,
            ];
        }
        return new Response(json_encode($output), 200, ['Content-Type' => 'application/javascript']);
    }

    /**
     * @Route("/museums/add", name="add_museum_page")
     */
    public function addAction(Request $request)
    {
        $museum = new Museum();

        $form   = $this->get('form.factory')->create(MuseumType::class, $museum);
        $museum->setEnabled(false);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($museum);

            $museum_history = new Museum_History();
            $museum_history->setDate(new \DateTime("now"));
            $museum_history->setAction("Insert");

            foreach ($museum->getImages() as $img){
                $img->setMuseum($museum);
            }
            $em->flush();
            $museum_history->setMuseumId($museum->getId());
            $em->persist($museum_history);

            $em->flush();

            return $this->redirectToRoute('index_museums_page');
        }

        return $this->render('@Museums/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/museums/edit/{id}", name="edit_museum_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $museum = $em->getRepository(Museum::class)->find($id);
        if(null === $museum)
        {
            throw new NotFoundHttpException("Museum with id: ".$id." doesn't exist");
        }

        $originalImages = new ArrayCollection();

        // Create an ArrayCollection of the current Image objects in the database
        foreach ($museum->getImages() as $image)
        {
            $originalImages->add($image);
        }

        $form = $this->createForm(MuseumType::class, $museum);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            foreach ($originalImages as $image)
            {
                if(false === $museum->getImages()->contains($image))
                {
                    // remove image
                    $em->remove($image);
                }
            }

            $museum_history = new Museum_History();
            $museum_history->setDate(new \DateTime("now"));
            $museum_history->setAction("Edit");
            $museum_history->setMuseumId($museum->getId());
            $em->persist($museum_history);

            $em->flush();
            return $this->redirectToRoute('index_museums_page');
        }

        return $this->render('@Museums/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/museums/delete/{id}", name="delete_museum_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $museum = $em->getRepository(Museum::class)->find($id);
        if(null === $museum)
        {
            throw new NotFoundHttpException('Museum with id: '.$id." doesn't exist");
        }

        $museum_history = new Museum_History();
        $museum_history->setDate(new \DateTime("now"));
        $museum_history->setAction("Delete");
        $museum_history->setMuseumId($museum->getId());
        $em->persist($museum_history);

        $em->remove($museum);
        $em->flush();

        return $this->redirectToRoute('index_museums_page');
    }

    /**
     * @Route("/museums/enable/{id}", name="enable_museum_page")
     */
    public function enableAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $museum = $em->getRepository(Museum::class)->find($id);
        if(null === $museum)
        {
            throw new NotFoundHttpException("Museum with id: ".$id." doesn't exist");
        }

        if($museum->getEnabled())
        {
            $museum->setEnabled(false);
        }
        else
        {
            $museum->setEnabled(true);
        }
        $em->flush();

        return $this->redirectToRoute('index_museums_page');
    }
}
