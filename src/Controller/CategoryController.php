<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
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
}
