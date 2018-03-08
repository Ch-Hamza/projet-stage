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
        return $this->render('IndexBundle::index.html.twig');
    }
}
