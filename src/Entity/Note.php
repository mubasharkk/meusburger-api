<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\NotebookController;
use App\Repository\NotebookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: NotebookRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Delete(),
        new Get(
            name: "get_by_slug",
            uriTemplate: "api/notes/slug/{id}",
            controller: NotebookController::class
        )
    ],
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
class Note
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups('read')]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 200)]
    #[Groups(['read', 'write'])]
    public string $headline;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups('read')]
    public string $slug;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read', 'write'])]
    private ?string $content;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('read')]
    public ?\DateTime $created_at = null;

    #[ORM\PrePersist]
    public function updatedTimestamps()
    {
        if ($this->created_at === null) {
            $this->created_at = new \DateTime('now');
        }
    }

    public function setHeadline(string $headline): self
    {
        $this->headline = $headline;
        $this->slug = self::slugify($headline);

        return $this;
    }

    public function setContent(string $content): self
    {
        /** @note: do some sanitization here! * */
        $this->content = htmlentities($content);

        return $this;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getContent(): string
    {
        return html_entity_decode($this->content);
    }

    /**
     * Source:
     * https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     *
     * @param  string  $text
     *
     * @return string
     */
    private static function slugify(string $text): ?string
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // remove duplicate divider
        $text = strtolower(
            preg_replace('~-+~', '-', trim($text, '-'))
        );

        return $text ?? null;
    }

}
