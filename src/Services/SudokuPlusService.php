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

}
