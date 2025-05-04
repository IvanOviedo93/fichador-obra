<?php

namespace App\Controller\Api;

use App\Entity\Obra;
use App\Repository\ObraRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ObraController
{
    #[Route('/api/obras', methods: ['GET'])]
    public function index(ObraRepository $obraRepository, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($obraRepository->findAll(), 'json', ['groups' => 'obra:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/obra/{id}', methods: ['GET'])]
    public function show(Obra $obra, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($obra, 'json', ['groups' => 'obra:read']);
        return new JsonResponse($data, 200, [], true);
    }

    #[Route('/api/obra', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $obra = $serializer->deserialize($request->getContent(), Obra::class, 'json');
        $errors = $validator->validate($obra);

        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $em->persist($obra);
        $em->flush();

        return new JsonResponse('Obra creada', 201);
    }

    #[Route('/api/obra/{id}', methods: ['PUT'])]
    public function update(Obra $obra, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {
        $serializer->deserialize($request->getContent(), Obra::class, 'json', ['object_to_populate' => $obra]);
        $em->flush();

        return new JsonResponse('Obra actualizada', 200);
    }

    #[Route('/api/obra/{id}', methods: ['DELETE'])]
    public function delete(Obra $obra, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($obra);
        $em->flush();

        return new JsonResponse('Obra eliminada', 200);
    }
}
