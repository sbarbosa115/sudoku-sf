<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SubmitSudokuPlusControllerTest extends WebTestCase
{

    public function testSubmitCorrectSudoku(): void
    {
        $client = static::createClient();

        $game = [
            [1,2,3,4],
            [3,4,1,2],
            [2,1,4,3],
            [4,3,2,1]
        ];

        ob_start();

        $csv = fopen('php://output', 'w');

        foreach ($game as $row) {
            fputcsv($csv, $row);
        }

        fclose($csv);

        $expected = ob_get_clean();

        ob_clean();

        $client->request(method: 'PUT', uri: '/submit', server: ['CONTENT_TYPE' => 'application/csv'], content: $expected);

        $response = $client->getResponse();

        // Response should be 200
        $this->assertEquals(200, $response->getStatusCode());
    }
}
