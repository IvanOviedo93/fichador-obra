<?php

namespace App\Controller\Api;

use App\Entity\Empleado;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/empleados')]
class EmpleadoController
{
    private EntityManagerInterface $em;

    // Inyección de dependencias en el constructor
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'api_empleados_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $empleados = $this->em->getRepository(Empleado::class)->findAll();

        $data = array_map(function ($empleado) {
            return [
                'id' => $empleado->getId(),
                'nombre' => $empleado->getNombre(),
                'email' => $empleado->getEmail(),
                'rol' => $empleado->getRol(),
            ];
        }, $empleados);

        return new JsonResponse($data);
    }

    #[Route('/new', name: 'api_empleados_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $empleado = new Empleado();
        $empleado->setNombre($data['nombre'] ?? 'Ivan Oviedo');
        $empleado->setEmail($data['email'] ?? 'ivan@prueba.com');
        $empleado->setPassword($data['password'] ?? '1234');
        $empleado->setRoles($data['rol'] ?? ['trabajador']);

        $this->em->persist($empleado);
        $this->em->flush();

        return new JsonResponse(['message' => 'Empleado creado', 'id' => $empleado->getId()], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_empleados_update', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $empleado = $em->getRepository(Empleado::class)->find($id);
        if (!$empleado) {
            return new JsonResponse(['error' => 'Empleado no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if (isset($data['nombre'])) {
            $empleado->setNombre($data['nombre']);
        }
        if (isset($data['email'])) {
            $empleado->setEmail($data['email']);
        }
        if (isset($data['rol'])) {
            $empleado->setRol($data['rol']);
        }
        if (isset($data['password'])) {
            $empleado->setPassword($data['password']); // Lo encriptaremos más adelante
        }

        $em->flush();

        return new JsonResponse(['message' => 'Empleado actualizado']);
    }

    #[Route('/{id}', name: 'api_empleados_delete', methods: ['DELETE'])]
public function delete(int $id, EntityManagerInterface $em): JsonResponse
{
    $empleado = $em->getRepository(Empleado::class)->find($id);
    if (!$empleado) {
        return new JsonResponse(['error' => 'Empleado no encontrado'], Response::HTTP_NOT_FOUND);
    }

    $em->remove($empleado);
    $em->flush();

    return new JsonResponse(['message' => 'Empleado eliminado']);
}

}
