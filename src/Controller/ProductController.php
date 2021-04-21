<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_productos")
     */
    public function listar(ProductRepository $repository,Request $request)
    {
        $products = $repository->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }


    /**
     * @Route("/product/new", name="product_new", methods={"GET","POST"})
     */
    public function create(Request $request,EntityManagerInterface $emi): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $emi->persist($product);
            $emi->flush();

            return $this->redirectToRoute('app_productos');
        }

        return $this->render('product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


}
