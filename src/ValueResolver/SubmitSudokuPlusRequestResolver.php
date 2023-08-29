<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Controller\Request\SubmitSudokuPlusRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubmitSudokuPlusRequestResolver implements ValueResolverInterface
{

    public function __construct(
        readonly SerializerInterface $serializer,
        readonly ValidatorInterface $validator
    )
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if ($argumentType !== SubmitSudokuPlusRequest::class) {
            return;
        }

        if ($request->files->count() === 0) {
            throw new BadRequestHttpException('No file provided');
        }

        $file = $request->files->all()[0];

        $csv = str_getcsv($file->getContent(), "\n");

        foreach ($csv as $key => $row) {
            $rowAsString = str_getcsv($row);

            foreach ($rowAsString as $rowAsStringKey => $value) {
                $rowAsString[$rowAsStringKey] = (int) $value;
            }

            $csv[$key] = $rowAsString;
        }

        $submitSudokuPlusRequest = new SubmitSudokuPlusRequest($csv);

        $errors = $this->validator->validate($submitSudokuPlusRequest);

        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        yield $submitSudokuPlusRequest;
    }
}
