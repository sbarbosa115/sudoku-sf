<?php

declare(strict_types=1);

namespace App\Services;

use App\Model\Grid;

class SudokuPlusService
{

    /**
     * This method will the grid of the sudoku. It will assume that data that reach this point is valid.
     * So it's responsibility will be to generate the grid.
     */
    public function generateGrid(int $gridLength): Grid
    {
        return new Grid($gridLength);
    }

    /**
     * This method was created for the scenario in which the user wants to submit an existing grid.
     */
    public function crateFromGrid(array $grid): Grid
    {
        return new Grid(count($grid), $grid);
    }

}
