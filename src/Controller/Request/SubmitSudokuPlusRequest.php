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
    public function validateGridSize(ExecutionContextInterface $context, mixed $payload): void
    {
        // Check that the CSV upload by the user has th correct structure.
        if (sqrt(count($this->grid)) !== floor(sqrt(count($this->grid)))) {
            $context->buildViolation('The game provide does not have the an correct structure!')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateNumbers(ExecutionContextInterface $context, mixed $payload): void
    {
        // Check that the CSV upload by the user has th correct structure.
        foreach ($this->grid as $rowKey => $row) {
            foreach ($row as $columnKey => $column) {
                $currentCellValue = $this->grid[$rowKey][$columnKey];
                if ($currentCellValue > count($this->grid) || $this->grid[$rowKey][$columnKey] < 0) {
                    $context->buildViolation(sprintf('The number provided [%s] is not valid in the position %s,%s', $currentCellValue, $rowKey, $columnKey))
                        ->atPath('grid[' . $rowKey. $columnKey . ']')
                        ->addViolation();
                }
            }
        }
    }

    #[Assert\Callback]
    public function validateRows(ExecutionContextInterface $context, mixed $payload): void
    {
        // Check that the grid has a valid Sudoku structure.
        foreach ($this->grid as $rowKey => $row) {
            // Check that the value is in only once in the column.
            if (count($row) !== count(array_unique($row))) {
                $context->buildViolation('The game provided contain errors in some of the rows!')
                    ->atPath('grid[' . $rowKey . ']')
                    ->addViolation();
            }
        }
    }

    #[Assert\Callback]
    public function validateColumns(ExecutionContextInterface $context, mixed $payload): void
    {
        // With the last row iteration we check columns
        foreach ($this->grid as $row) {
            foreach ($row as $columnKey => $column) {
                $column = array_column($this->grid, $columnKey);
                if (count($column) !== count(array_unique($column))) {
                    $context->buildViolation('The game provided contain errors in some of the columns!')
                        ->atPath('grid[' . $columnKey . ']')
                        ->addViolation();
                }
            }
            break;
        }
    }

    #[Assert\Callback]
    public function validateGrid(ExecutionContextInterface $context, mixed $payload): void
    {
        // Check in each subgrid that the value is only once.
        $subgridSize = (int) sqrt(count($this->grid));

        $startRow = 0;
        $rowCounter = 0;
        $elementsInSubGrid = [];

        while($startRow < count($this->grid)) {
            $endRow = ($startRow === 0) ? $subgridSize - 1 :  ($startRow - 1) + $subgridSize;

            for ($rowChildRange = $startRow; $rowChildRange <= $endRow; $rowChildRange++) {

                $starColumn = 0;
                while($starColumn < count($this->grid[$rowChildRange])) {
                    $endColumn = ($starColumn === 0) ? $subgridSize - 1 :  ($starColumn - 1) + $subgridSize;

                    if (!array_key_exists($rowCounter, $elementsInSubGrid)) {
                        $elementsInSubGrid[$rowCounter] = [];
                    }

                    if (!array_key_exists($starColumn, $elementsInSubGrid[$rowCounter])) {
                        $elementsInSubGrid[$rowCounter][$starColumn] = [];
                    }

                    $elementsInSubGrid[$rowCounter][$starColumn] = [
                        ...$elementsInSubGrid[$rowCounter][$starColumn],
                        ...array_slice($this->grid[$rowChildRange], $starColumn, $subgridSize)];

                    $starColumn = $endColumn + 1;
                }
            }

            // Check for uniqueness in the subgrid
            foreach ($elementsInSubGrid[$rowCounter] as $subgrid) {
                if (count($subgrid) !== count(array_unique($subgrid))) {
                    $context->buildViolation('The game provided contain errors in some of the sub-grids!')
                        ->addViolation();
                }
            }

            $startRow = $endRow + 1;
            $rowCounter++;
        }

    }

}
