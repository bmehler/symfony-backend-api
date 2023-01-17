<?php

namespace App\Controller;

use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/api', name:'api_')]
class EmployeeController extends AbstractController
{
    #[Route('/employee', name: 'app_employee', methods:['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $employees = $doctrine
            ->getRepository(Employee::class)
            ->findAll();
        
        $data = [];
  
        foreach ($employees as $employee) {
           $data[] = [
                'id' => $employee->getId(),
                'firstname' => $employee->getFirstname(),
                'surname' => $employee->getSurname(),
                'position' => $employee->getPosition(),
                'salary' => $employee->getSalary()
           ];
        }
        
        return $this->json($data);
    }

    #[Route('/employee', name:"employee_new", methods:['POST'])]
    public function new(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();

        $data = json_decode($request->getContent(), true);
  
        $employee = new Employee();
        $employee->setFirstname($data['firstname']);
        $employee->setSurname($data['surname']);
        $employee->setPosition($data['position']);
        $employee->setSalary($data['salary']);
  
        $entityManager->persist($employee);
        $entityManager->flush();
  
        return $this->json('Created new employee successfully with id ' . $employee->getId());
    }

    #[Route('/employee/{id}', name:'employee_show', methods:['GET'])]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $employee = $doctrine->getRepository(Employee::class)->find($id);
  
        if (!$employee) {
  
            return $this->json('No employee found for id' . $id, 404);
        }
  
        $data =  [
            'id' => $employee->getId(),
            'firstname' => $employee->getFirstname(),
            'surname' => $employee->getSurname(),
            'position' => $employee->getPosition(),
            'salary' => $employee->getSalary(),
        ];
          
        return $this->json($data);
    }

    #[Route('/employee/{id}', name:'employee_edit', methods:['PUT'])]
    public function edit(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $employee = $entityManager->getRepository(Employee::class)->find($id);

        $data = json_decode($request->getContent(), true);
  
        if (!$employee) {
            return $this->json('No employee found for id' . $id, 404);
        }
  
        $employee->setFirstname($data['firstname']);
        $employee->setSurname($data['surname']);
        $employee->setPosition($data['position']);
        $employee->setSalary($data['salary']);
        $entityManager->persist($employee);
        $entityManager->flush();
  
        $data =  [
            'id' => $employee->getId(),
            'firstname' => $employee->getFirstname(),
            'surname' => $employee->getSurname(),
            'position' => $employee->getPosition(),
            'salary' => $employee->getSalary(),
        ];
          
        return $this->json($data);
    }

    #[Route('/employee/{id}', name:'employee_delete', methods:['DELETE'])]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $employee = $entityManager->getRepository(Employee::class)->find($id);
  
        if (!$employee) {
            return $this->json('No employee found for id ' . $id, 404);
        }
  
        $entityManager->remove($employee);
        $entityManager->flush();
  
        return $this->json('Deleted a employee successfully with id ' . $id);
    }
}
