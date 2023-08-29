<?php

declare(strict_types=1);


namespace App\Controller;

use App\Controller\Request\CreateSudokuPlusRequest;
use App\Services\SudokuPlusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/create', name: 'create_sudoku_plus', methods: ['POST'])]
class CreateSudokuPlusController extends AbstractController
{

    public function __construct(
        readonly SudokuPlusService $sudokuPlusService
    ){
    }

    public function __invoke(CreateSudokuPlusRequest $request): JsonResponse
    {
        return $this->json($this->sudokuPlusService->generateGrid($request->gridSize)->toArray());
    }

}

