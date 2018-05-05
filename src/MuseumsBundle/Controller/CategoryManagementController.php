<?php

namespace MuseumsBundle\Controller;

use MuseumsBundle\Entity\Category;
use MuseumsBundle\Entity\History\Category_History;
use MuseumsBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Date;

class CategoryManagementController extends Controller
{
    /**
     * @Route("/categories", name="index_category_page")
     */
    public function indexAction()
    {
        $listCategories = $this->getDoctrine()
            ->getManager()
            ->getRepository(Category::class)
            ->findAll()
        ;

        return $this->render('@Museums/Category/list.html.twig', array(
            'listCategories' => $listCategories
        ));
    }

    /**
     * @Route("/categories/add", name="add_category_page")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form   = $this->get('form.factory')->create(CategoryType::class, $category);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $category_history = new Category_History();
            $category_history->setDate(new \DateTime("now"));
            $category_history->setAction("Insert");
            $category_history->setCategoryId($category->getId());
            $em->persist($category_history);
            $em->flush();

            return $this->redirectToRoute('index_category_page');
        }

        return $this->render('@Museums/Category/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/categories/edit/{id}", name="edit_category_page")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        if(null === $category)
        {
            throw new NotFoundHttpException("Category with id: ".$id." doesn't exist");
        }
        $form = $this->createForm(CategoryType::class, $category);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid())
        {
            $category_history = new Category_History();
            $category_history->setDate(new \DateTime("now"));
            $category_history->setAction("Edit");
            $category_history->setCategoryId($category->getId());
            $em->persist($category_history);

            $em->flush();
            return $this->redirectToRoute('index_category_page');
        }

        return $this->render('@Museums/Category/edit.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/categories/delete/{id}", name="delete_category_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        if(null === $category)
        {
            throw new NotFoundHttpException('Category with id: '.$id." doesn't exist");
        }
        $category_history = new Category_History();
        $category_history->setDate(new \DateTime("now"));
        $category_history->setAction("Delete");
        $category_history->setCategoryId($category->getId());
        $em->persist($category_history);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('index_category_page');
    }
}
