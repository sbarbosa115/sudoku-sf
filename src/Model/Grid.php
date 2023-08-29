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
            $this->prepareGridForPlay();
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

    /**
     * Takes the current grid and prepare it for the game removing some numbers.
     * @param int $difficulty How empty will be the grid. The higher the number the more empty the grid will be.
     */
    public function prepareGridForPlay(int $difficulty = 70): void
    {
        $cellsToEmpty = (int) round(floor(($difficulty / 100) * count($this->cells)));
        foreach ($this->cells as $row => $columns) {
            $keysToBeEmpty = array_rand($this->cells[$row], $cellsToEmpty);

            foreach ($keysToBeEmpty as $key) {
                $this->cells[$row][$key] = 0;
            }
        }
    }

    public function toArray(): array
    {
        return $this->cells;
    }
}
