<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Product;
use App\Form\CustomerType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/products", name="app_product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_list", methods={"GET"})
     */
    public function productsAction(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->json($products);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(int $id, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return $this->json('No product found for id ' . $id, Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, Response::HTTP_OK);
    }

    /**
     * @Route("", name="_new", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->persist($product);
        $entityManager->flush();

        $data = [
            'content' => $product,
            'group' => 'customer'
        ];

        return $this->json($data, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="_edit", methods={"PUT"})
     */
    public function edit(Request $request, int $id, Product $product, ManagerRegistry $doctrine): JsonResponse
    {
        $form = $this->createForm(ProductType::class, $product);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        $doctrine->getManager()->flush();

        $data = [
            'content' => $product,
            'group' => 'customer'
        ];

        return $this->json($data);
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(int $id, Product $product, ManagerRegistry $doctrine): JsonResponse
    {
        if (!$product) {
            return $this->json('No product found for id ' . $id, 404);
        }
        $entityManager = $doctrine->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        return $this->json('Deleted a product successfully with id ' . $id);
    }
}
