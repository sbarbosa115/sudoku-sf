<?php

declare(strict_types=1);

namespace App\Model;

/**
 * This class represent the grid of the sudoku.
 */
class Grid
{

    private array $cells;

    public function __construct(int $gridLength, array $cells = [])
    {
        if (count($cells) === 0) {
            $this->cells = array_fill(0, $gridLength, array_fill(0, $gridLength, 0));
            $this->generateNumbers();
        } else {
            $this->cells = $cells;
        }
    }


    public function generateNumbers(int $row = 0, int $column = 0): bool
    {
        $size = count($this->cells);
        if ($row == $size - 1 && $column == $size) {
            return true;
        }

        if ($column == $size) {
            $row++;
            $column = 0;
        }

        if ($this->cells[$row][$column] != 0) {
            return $this->generateNumbers($row, $column + 1);
        }

        $nums = range(1, $size);
        shuffle($nums);

        foreach ($nums as $num) {
            if ($this->isValidNumber($row, $column, $num)) {
                $this->cells[$row][$column] = $num;
                if ($this->generateNumbers($row, $column + 1)) {
                    return true;
                }
                $this->cells[$row][$column] = 0;
            }
        }

        return false;
    }

    private function isValidNumber(int $row, int $col, int $num): bool
    {
        $size = count($this->cells);

        for ($i = 0; $i < $size; $i++) {
            if ($this->cells[$row][$i] == $num || $this->cells[$i][$col] == $num) {
                return false;
            }
        }

        $subgridSize = sqrt($size);
        $startRow = $row - $row % $subgridSize;
        $startCol = $col - $col % $subgridSize;

        for ($i = $startRow; $i < $startRow + $subgridSize; $i++) {
            for ($j = $startCol; $j < $startCol + $subgridSize; $j++) {
                if ($this->cells[$i][$j] == $num) {
                    return false;
                }
            }
        }

        return true;
    }

    public function toArray(): array
    {
        return $this->cells;
    }
}
