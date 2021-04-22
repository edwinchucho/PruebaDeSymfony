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
     * @Route("/product", name="app_productos")
     */
    public function show(ProductRepository $repository,Request $request,PaginatorInterface $paginator)
    {
        $query = $repository->findAll();
        $products = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 5);

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

            return $this->redirectToRoute('product_new');
        }

        return $this->render('product/create.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/product/edit/{id}", name="product_edit", methods={"GET","POST"})
     */
    public function edit($id,Request $request, EntityManagerInterface $emi,ProductRepository $repository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $Product = $repository->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $Product->setCode($form->get('code')->getData());
            $Product->setName($form->get('name')->getData());
            $Product->setDescription($form->get('description')->getData());
            $Product->setBrand($form->get('brand')->getData());
            $Product->setPrice($form->get('price')->getData());

            $emi->flush();

            return $this->redirectToRoute('app_productos');
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete($id,EntityManagerInterface $em,ProductRepository $repository)
    {
        $query = $repository->find($id);
        $em->remove($query);
        $em->flush();
        return new Response(null, 204);
    }


}
