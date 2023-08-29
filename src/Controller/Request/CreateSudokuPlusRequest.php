<?php

declare(strict_types=1);


namespace App\Controller\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class CreateSudokuPlusRequest
{

    #[Assert\NotBlank]
    public int $gridSize;

    public function __construct(int $gridSize = 9)
    {
        $this->gridSize = $gridSize;
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        if (sqrt($this->gridSize) !== floor(sqrt($this->gridSize))) {
            $context->buildViolation('Number provider is not valid for a grid size!')
                ->addViolation();
        }
    }
}
