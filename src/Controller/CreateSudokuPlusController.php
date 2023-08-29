<?php

declare(strict_types=1);


namespace App\Controller;

use App\Controller\Request\CreateSudokuPlusRequest;
use App\Services\SudokuPlusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/create', name: 'create_sudoku_plus', methods: ['POST'])]
class CreateSudokuPlusController extends AbstractController
{

    public function __construct(
        readonly SudokuPlusService $sudokuPlusService,
        readonly SerializerInterface $serializer
    ){
    }

    public function __invoke(CreateSudokuPlusRequest $request): Response
    {
        $game = $this->sudokuPlusService->generateGrid($request->gridSize)->toArray();

        $csvContent = $this->serializer->serialize($game, 'csv');

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="sudoku-plus.csv"');

        return $response;
    }

}

