<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManagerInterface;


class EmployeeControllerTest extends WebTestCase
{
    public function testGetAllEmployees()
    {
        static::createClient()->request('GET', '/api/employee');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, '');
        
    }

    public function testCreateNewEmployee()
    {
        static::createClient()->jsonRequest('POST', '/api/employee', [
            'firstname' => 'Maximilian',
            'surname' => 'Muster',
            'position' => 'Musterposition',
            'salary' => 5000
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, '');

    }

    public function testUpdateEmployee()
    {
        static::createClient()->jsonRequest('PUT', '/api/employee/2', [
            'firstname' => 'Michaela',
            'surname' => 'Muster',
            'position' => 'Musterposition',
            'salary' => 4999
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, '');

    }

    public function testDeleteEmployee()
    {
        static::createClient()->jsonRequest('DELETE', '/api/employee/3');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, '');

    }
}