<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @Route("/", name="app_productos")
     */
    public function show(ProductRepository $repository,Request $request,PaginatorInterface $paginator)
    {
        $p = $request->query->get('p');
        $query = $repository->getSearchQuery($p);

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
    public function create(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','se registro su producto');

            return $this->redirectToRoute('product_new');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/product/edit/{id}", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Product $product,Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('app_productos');
        }
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function delete(Product $product)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('app_productos');
    }


    /**
     * @Route("/sendEmail",)
     */
    public function email(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('edwinchucho@hotmail.com')
            ->to('newUser@hotmail.com')
            ->subject('envio de email pruebaSymfony')
            ->text("use el DSN con mailtrap,colocar la ruta /sendEmail y envia automaticamente ");
        $mailer->send($email);
        return $this->redirectToRoute('app_productos');
    }

}
