<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Product;
use App\Enum\StatusEnum;
use App\Form\CustomerType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
     * @Route("/", name="_list", methods={"GET"})
     */
    public function productsAction(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();

        return $this->json($products, Response::HTTP_OK, [], ['groups' => 'product']);
    }

    /**
     * @Route("/{id}", name="_show", methods={"GET"})
     */
    public function show(?Product $product, $id): JsonResponse
    {
        if (!$product) {
            return $this->json('No product found for id ' . $id, Response::HTTP_NOT_FOUND);
        }

        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'product']);
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
        try {
            $entityManager->persist($product);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            $message = $exception->getMessage();
            $uniqueConstraintReferenceViolation = strstr($message, 'UNIQ');
            if ($uniqueConstraintReferenceViolation) {
                throw new BadRequestException("Issn field must be unique");
            }

        }

            $data = ['content' => $product];

        return $this->json($data, Response::HTTP_CREATED, [], ['groups' => 'product']);
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
        $product->setUpdatedAtValue();
        $doctrine->getManager()->flush();

        $data = ['content' => $product];

        return $this->json($data, Response::HTTP_OK, [], ['groups' => 'product']);
    }

    /**
     * @Route("/{id}", name="_delete", methods={"DELETE"})
     */
    public function delete(Product $product, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        if ($product->getDeletedAt() === null) {
            $product->setStatus(StatusEnum::STATUS_DELETED);
            $product->setDeletedAtValue();
            $entityManager->persist($product);
            $entityManager->flush();
        }
        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'product']);
    }
}
