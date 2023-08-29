<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Controller\Request\CreateSudokuPlusRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateSudokuPlusRequestResolver  implements ValueResolverInterface
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

        if ($argumentType !== CreateSudokuPlusRequest::class) {
            return;
        }

        $createSudokuPlusRequest = $this->serializer->deserialize(
            $request->getContent(),
            CreateSudokuPlusRequest::class,
            'json'
        );

        $errors = $this->validator->validate($createSudokuPlusRequest);

        if (count($errors) > 0) {
            throw new BadRequestHttpException((string) $errors);
        }

        yield $createSudokuPlusRequest;
    }
}
