<?php

namespace App\Controller\Api;

use App\Entity\Empleado;
use App\Repository\EmpleadoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

class EmpleadoController extends AbstractController
{
    /**
     * @Route("/api/empleados", name="api_empleados", methods={"GET"})
     */
    public function getEmpleados(EmpleadoRepository $empleadoRepository, SerializerInterface $serializer)
    {
        $empleados = $empleadoRepository->findAll();
        $data = $serializer->serialize($empleados, 'json', ['groups' => 'empleado:read']);

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/api/empleado", name="api_create_empleado", methods={"POST"})
     */
    public function createEmpleado(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->getContent();
        $empleado = $serializer->deserialize($data, Empleado::class, 'json');

        // Validar la entidad
        $errors = $validator->validate($empleado);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }

        $entityManager->persist($empleado);
        $entityManager->flush();

        return new JsonResponse('Empleado creado con éxito', 201);
    }

    /**
     * @Route("/api/empleado/{id}", name="api_get_empleado", methods={"GET"})
     */
    public function getEmpleado($id, EmpleadoRepository $empleadoRepository, SerializerInterface $serializer)
    {
        $empleado = $empleadoRepository->find($id);

        if (!$empleado) {
            return new JsonResponse('Empleado no encontrado', 404);
        }

        $data = $serializer->serialize($empleado, 'json', ['groups' => 'empleado:read']);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/api/empleado/{id}", name="api_update_empleado", methods={"PUT"})
     */
    public function updateEmpleado($id, Request $request, EmpleadoRepository $empleadoRepository, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $empleado = $empleadoRepository->find($id);

        if (!$empleado) {
            return new JsonResponse('Empleado no encontrado', 404);
        }

        $data = $request->getContent();
        $serializer->deserialize($data, Empleado::class, 'json', ['object_to_populate' => $empleado]);

        $entityManager->flush();

        return new JsonResponse('Empleado actualizado', 200);
    }

    /**
     * @Route("/api/empleado/{id}", name="api_delete_empleado", methods={"DELETE"})
     */
    public function deleteEmpleado($id, EmpleadoRepository $empleadoRepository, EntityManagerInterface $entityManager)
    {
        $empleado = $empleadoRepository->find($id);

        if (!$empleado) {
            return new JsonResponse('Empleado no encontrado', 404);
        }

        $entityManager->remove($empleado);
        $entityManager->flush();

        return new JsonResponse('Empleado eliminado', 200);
    }
}
