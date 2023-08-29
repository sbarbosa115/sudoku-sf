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

        $client->request(method: 'POST', uri: '/submit', files: [
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

        $client->request(method: 'POST', uri: '/submit', files: [
            $file
        ], server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('The game provided contain errors in some of the rows!', $response->getContent());
    }


    public function testSubmitNoFileSudoku(): void
    {
        $client = static::createClient();

        $client->request(method: 'POST', uri: '/submit', server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('{"message":"No file provided","code":0}', $response->getContent());
    }

    /**
     * This covers scenarios in which numbers are not allowed.
     */
    public function testSubmitIncorrectNumbersSudoku(): void
    {
        $client = static::createClient();

        $file = new UploadedFile(
            dirname(__FILE__) . '/_data/sudoku-incorrect-data-forbidden-numbers.csv',
            'file.csv',
        );

        $client->request(method: 'POST', uri: '/submit', files: [
            $file
        ], server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('The number provided [36] is not valid in the position 8,8\n","code":0}', $response->getContent());
        $this->assertStringContainsString('The number provided [-1] is not valid in the position 5,3', $response->getContent());
    }

    /**
     * This covers scenarios in which numbers are not allowed.
     */
    public function testSubmitIncorrectCharactersSudoku(): void
    {
        $client = static::createClient();

        $file = new UploadedFile(
            dirname(__FILE__) . '/_data/sudoku-incorrect-data-characters.csv',
            'file.csv',
        );

        $client->request(method: 'POST', uri: '/submit', files: [
            $file
        ], server: ['CONTENT_TYPE' => 'application/csv']);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('CSV contains non-number values.', $response->getContent());
    }
}
