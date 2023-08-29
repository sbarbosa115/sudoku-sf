<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SubmitSudokuPlusControllerTest extends WebTestCase
{

    public function testSubmitCorrectSudoku(): void
    {
        $client = static::createClient();

        $file = new UploadedFile(
            dirname(__FILE__) . '/_data/sudoku-correct-data.csv',
            'file.csv',
        );

        $client->request(method: 'PUT', uri: '/submit', files: [
            $file
        ], server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        // Response should be 200
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"message":"Congratulations, you completed the game!"}', $response->getContent());
    }

    public function testSubmitIncorrectDataSudoku(): void
    {
        $client = static::createClient();

        $file = new UploadedFile(
            dirname(__FILE__) . '/_data/sudoku-incorrect-data.csv',
            'file.csv',
        );

        $client->request(method: 'PUT', uri: '/submit', files: [
            $file
        ], server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('The game provided contain errors in some of the rows!', $response->getContent());
    }


    public function testSubmitNoFileSudoku(): void
    {
        $client = static::createClient();


        $client->request(method: 'PUT', uri: '/submit', server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('{"message":"No file provided","code":0}', $response->getContent());
    }
}
