<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\Grid;
use PHPUnit\Framework\TestCase;

class GridTest extends TestCase
{

    private function assertValidNumber(array $cells, int $row, int $col, int $value): void
    {

        $this->assertTrue(in_array($value, $cells[$row]));

        $this->assertTrue(in_array($value, array_column($cells, $col)));

        $size = count($cells);

        $subgridSize = sqrt($size);
        $startRow = $row - $row % $subgridSize;
        $startCol = $col - $col % $subgridSize;

        $inSubgrid = false;
        for ($i = $startRow; $i < $startRow + $subgridSize; $i++) {
            for ($j = $startCol; $j < $startCol + $subgridSize; $j++) {
                if ($cells[$i][$j] == $value) {
                    $inSubgrid = true;
                }
            }
        }

        $this->assertTrue($inSubgrid);
    }

    public function testCorrectnessRowAndColumnGeneration(): void
    {
        $grid = new Grid(4);

        foreach ($grid->toArray() as $rowKey => $row) {
            $this->assertCount(4, $row);

            foreach ($row as $columnKey => $column) {

                $this->assertValidNumber($grid->toArray(), $rowKey, $columnKey, $grid->toArray()[$rowKey][$columnKey]);
                $this->assertIsInt($column);
                $this->assertGreaterThanOrEqual(0, $column);
                $this->assertLessThanOrEqual(4, $column);
            }

        }
        $this->assertTrue(true);
    }

}
