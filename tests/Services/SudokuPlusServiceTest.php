<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Services\SudokuPlusService;
use PHPUnit\Framework\TestCase;
class SudokuPlusServiceTest extends TestCase
{

    public function generateGridProvider(): array
    {
        return [
            [4],
            [9],
            [16]
        ];
    }

    /**
     * Testing known scenarios.
     * @dataProvider generateGridProvider
     */
    public function testGenerateGrid(int $gridSize): void
    {
        $service = new SudokuPlusService();

        $grid = $service->generateGrid($gridSize);

        $this->assertCount($gridSize, $grid->toArray());
        $this->assertIsArray($grid->toArray());
    }

}
