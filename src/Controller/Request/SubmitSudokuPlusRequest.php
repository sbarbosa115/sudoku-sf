<?php

declare(strict_types=1);

namespace App\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SubmitSudokuPlusRequest
{

    public array $grid;

    public function __construct(array $grid)
    {
        $this->grid = $grid;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        // Check that the CSV upload by the user has th correct structure.
        if (sqrt(count($this->grid)) !== floor(sqrt(count($this->grid)))) {
            $context->buildViolation('The game provide does not have the an correct structure!')
                ->addViolation();
        }

        // Check that the grid has a valid Sudoku structure.
        foreach ($this->grid as $rowKey => $row) {
            // Check that the value is in only once in the column.
            if (count($row) !== count(array_unique($row))) {
                $context->buildViolation('The game provided contain errors in some of the rows!')
                    ->atPath('grid[' . $rowKey . ']')
                    ->addViolation();
            }


            // Check in each subgrid that the value is only once.
            $subgridSize = sqrt(count($this->grid));
            $startRow = $rowKey - $rowKey % $subgridSize;

            //foreach ($row as $column) {
            //    $startCol = $column - $column % $subgridSize;
            //
            //    $valuesInTheSubGrid = [];
            //    for ($i = $startRow; $i < $startRow + $subgridSize; $i++) {
            //        for ($j = $startCol; $j < $startCol + $subgridSize; $j++) {
            //            $valuesInTheSubGrid[] = $this->grid[$i][$j];
            //        }
            //    }
            //
            //    if (count($valuesInTheSubGrid) !== count(array_unique($valuesInTheSubGrid))) {
            //        $context->buildViolation('The game provided contain errors in some of the sub-grids!')
            //            ->addViolation();
            //    }
            //}
        }

        // With the last row iteration we check columns
        foreach ($row as $columnKey => $column) {
            $column = array_column($this->grid, $columnKey);
            if (count($column) !== count(array_unique($column))) {
                $context->buildViolation('The game provided contain errors in some of the columns!')
                    ->atPath('grid[' . $columnKey . ']')
                    ->addViolation();
            }
        }


    }

}
