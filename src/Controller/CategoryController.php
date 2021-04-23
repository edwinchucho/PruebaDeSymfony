<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function show(CategoryRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $repository->findAll();

        $categories = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 5);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/new", name="category_new")
     */
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $category = $form->getData();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_new');

        }

        return $this->render('category/create.html.twig',[
            'category'=> $category,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/category/edit/{id}", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Category $category,Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
