<?php

namespace App\Controller\Api;

use App\Entity\Herramienta;
use App\Repository\HerramientaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HerramientaController extends AbstractController
{
    #[Route('/api/herramientas', methods: ['GET'])]
    public function index(HerramientaRepository $repo, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($repo->findAll(), 'json', ['groups' => 'herramienta:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/herramienta/{id}', methods: ['GET'])]
    public function show(Herramienta $herramienta, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($herramienta, 'json', ['groups' => 'herramienta:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/herramienta', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $herramienta = $serializer->deserialize($request->getContent(), Herramienta::class, 'json');
        $errors = $validator->validate($herramienta);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $em->persist($herramienta);
        $em->flush();

        return new JsonResponse('Herramienta creada', 201);
    }

    #[Route('/api/herramienta/{id}', methods: ['PUT'])]
    public function update(Herramienta $herramienta, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Herramienta::class, 'json', ['object_to_populate' => $herramienta]);
        $em->flush();

        return new JsonResponse('Herramienta actualizada', 200);
    }

    #[Route('/api/herramienta/{id}', methods: ['DELETE'])]
    public function delete(Herramienta $herramienta, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($herramienta);
        $em->flush();

        return new JsonResponse('Herramienta eliminada', 200);
    }
}
