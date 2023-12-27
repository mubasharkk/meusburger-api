<?php

namespace App\Requests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseRequest
{

    public function __construct(protected ValidatorInterface $validator)
    {
        $this->populate();
    }

    public function validate():array
    {
        $errors = $this->validator->validate($this);

        $messages = ['errors' => []];

        /** @var \Symfony\Component\Validator\ConstraintViolation $message */
        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value'    => $message->getInvalidValue(),
                'message'  => $message->getConstraint()->message ?? $message->getMessage(),
            ];
        }

        return $messages;
    }

    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    protected function populate(): void
    {
        foreach ($this->getRequest()->request->all() as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}
