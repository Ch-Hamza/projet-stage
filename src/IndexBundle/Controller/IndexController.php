<?php

namespace IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index_page")
     */
    public function indexAction()
    {
        $total_museums = $this->getDoctrine()->getRepository('MuseumsBundle:Museum')->count_museums();
        $total_expositions = $this->getDoctrine()->getRepository('ExpositionBundle:Exposition')->count_expositions();
        $total_users = $this->getDoctrine()->getRepository('UserBundle:User')->count_users();
        //$profile = $this->getUser();
        return $this->render('IndexBundle::index.html.twig', array(
            'total_museums' => $total_museums,
            'total_expositions' => $total_expositions,
            'total_users' => $total_users,
        ));
    }
}
