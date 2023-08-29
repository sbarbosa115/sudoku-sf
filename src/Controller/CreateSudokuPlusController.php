<?php

declare(strict_types=1);


namespace App\Controller;

use App\Controller\Request\CreateSudokuPlusRequest;
use App\Services\SudokuPlusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

#[Route('/create', name: 'create_sudoku_plus', methods: ['POST'])]
class CreateSudokuPlusController extends AbstractController
{

    public function __construct(
        readonly SudokuPlusService $sudokuPlusService,
        readonly EncoderInterface $encoder
    ){
    }

    public function __invoke(CreateSudokuPlusRequest $request): Response
    {
        $game = $this->sudokuPlusService->generateGrid($request->gridSize)->toArray();

        $csvContent = $this->encoder->encode($game, 'csv', [CsvEncoder::NO_HEADERS_KEY => true]);

        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="sudoku-plus.csv"');

        return $response;
    }

}

