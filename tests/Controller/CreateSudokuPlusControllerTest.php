<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateSudokuPlusControllerTest extends WebTestCase
{

    public function testCreateSudokuPlusCorrectScenario(): void
    {
        $client = static::createClient();

        $client->request(method: 'POST', uri: '/create', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'gridSize' => 9
        ]));

        $response = $client->getResponse();

        // Response should be 200
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testFailedResponseWrongGridLength(): void
    {
        $client = static::createClient();

        $client->request(method: 'POST', uri: '/create', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'gridSize' => 5
        ]));

        $response = $client->getResponse();

        // Response should be 400
        $this->assertEquals(400, $response->getStatusCode());

    }

}
