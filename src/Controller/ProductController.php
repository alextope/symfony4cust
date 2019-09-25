<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ProductController extends FOSRestController
{
    /**
     * @Route("/product", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
            $products = $productRepository->findAll();
            return $this->handleView($this->view($products));
    }

    /**
     * @Route("/product", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request)//: Response
    {
        $product = new Product();
        $product->setCreatedAt(new \DateTime());
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
            //return $this->redirectToRoute('product_index');
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * @Route("/product/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->handleView($this->view($product));
    }

    /**
     * @Route("/product/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product)
    {
        $product->setUpdatedAt(new \DateTime());
        
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        
        $form->submit($data);

        //$form = $this->createForm(ProductType::class, $product);
        //$form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
