<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\SubmitSudokuPlusRequest;
use App\Services\SudokuPlusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

// This will allow player to upload a sudoku plus game to be validated.
#[Route('/submit', name: 'submit_sudoku_plus', methods: ['POST'])]
class SubmitSudokuPlusController extends AbstractController
{

    public function __construct(
        readonly SudokuPlusService $sudokuPlusService
    ){
    }

    public function __invoke(SubmitSudokuPlusRequest $request): JsonResponse
    {
        return $this->json([
            'message' => 'Congratulations, you completed the game!',
        ]);
    }

}

