<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Enum\StatusEnum;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/customers", name="app_customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="_list", methods={"GET"})
     */
    public function customersAction(CustomerRepository $customerRepository)
    {
        $customers = $customerRepository->findAll();

        return $this->json($customers);
    }

    /**
     * @Route("", name="_new", methods={"POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->persist($customer);
        $entityManager->flush();

        $data = [
            'content' => $customer,
            'group' => 'customer'
        ];

        return $this->json($data, Response::HTTP_CREATED);
    }

    /**
     * @Route("/{uuid}", name="_show", methods={"GET"})
     */
    public function show(?Customer $customer, $uuid): JsonResponse
    {
        if (!$customer) {
            return $this->json('No customer found for id ' . $uuid, Response::HTTP_NOT_FOUND);
        }

        return $this->json($customer);
    }

    /**
     * @Route("/{uuid}", name="_edit", methods={"PUT"})
     */
    public function edit(Request $request, Customer $customer, ManagerRegistry $doctrine): JsonResponse
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }
        
        $customer->setUpdatedAtValue();
        $doctrine->getManager()->flush();

        $data = [
            'content' => $customer,
            'group' => 'customer'
        ];

        return $this->json($data);
    }

    /**
     * @Route("/{uuid}", name="_delete", methods={"DELETE"})
     */
    public function delete(?Customer $customer, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        if ($customer->getDeletedAt() === null) {
            $customer->setStatus(StatusEnum::STATUS_DELETED);
            $customer->setDeletedAtValue();

            $entityManager->persist($customer);
            $entityManager->flush();
        }
        return $this->json($customer);
    }

}