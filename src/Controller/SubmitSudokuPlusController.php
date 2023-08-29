<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Request\SubmitSudokuPlusRequest;
use App\Services\SudokuPlusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/submit', name: 'submit_sudoku_plus', methods: ['PUT'])]
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

