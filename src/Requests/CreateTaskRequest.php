<?php

namespace App\Requests;

use App\Entity\Task;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTaskRequest extends BaseRequest
{

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    public string $title;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Choice(callback: [Task::class, 'getStatuses'])]
    public string $status;

    #[Assert\Type('string')]
    public ?string $description = null;

    #[Assert\AtLeastOneOf(constraints: [
        new Assert\DateTime,
        new Assert\Date
    ], message: "Invalid due-date given. Valid formats are 'Y-m-d', 'Y-m-d H:i:s'")]
    public ?string $due_date = null;

    #[Assert\Type(type: 'array', message: 'Tags can only be accepted as array of strings.')]
    public $tags = [];

}
