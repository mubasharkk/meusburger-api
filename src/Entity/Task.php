<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups('read')]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull(message: 'Title cannot be empty.')]
    public string $title;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Groups(['read', 'write'])]
    #[Assert\NotNull]
    #[Assert\Choice(
        choices: ['to-do', 'in-progress', 'completed', 'done', 'overdue'],
        message: 'Choice a valid status.'
    )]
    public string $status;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['read', 'write'])]
    #[Assert\DateTime(message: 'Invalid due-date given.')]
    public ?\DateTime $due_date = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['read', 'write'])]
    public array $tags = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('read')]
    public ?\DateTime $created_at = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if ($this->created_at === null) {
            $this->created_at = new \DateTime('now');
        }
    }

    public function setDescription(string $description)
    {
        /** @note: do some sanitization here! * */
        $this->description = htmlentities($description);
    }
}
