<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function read(CategoryRepository $repository): Response
    {
        $categories = $repository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/new", name="category_new")
     */
    public function create(Request $request,EntityManagerInterface $emi){
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $emi->persist($category);
            $emi->flush();

            return $this->redirectToRoute('category_new');

        }

        return $this->render('category/create.html.twig',[
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/category/edit/{id}", name="category_edit", methods={"GET","POST"})
     */
    public function edit($id,Request $request, EntityManagerInterface $emi,CategoryRepository $repository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $Category = $repository->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Category->setName($form->get('name')->getData());
            $Category->setActive($form->get('active')->getData());

            $emi->flush();

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/category/delete/{id}", name="category_delete", methods={"DELETE"})
     */
    public function delete($id,EntityManagerInterface $em,ProductRepository $repository)
    {
        $query = $repository->find($id);
        $em->remove($query);
        $em->flush();
        return new Response(null, 204);
    }
}
