<?php
namespace App\Model;

use ArrayObject;
use DateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Validator\Constraints as Assert;

class BlogPostModel implements Persistable
{
    /**
     * @var string|null
     *
     * @Assert\Type(type="string")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     */
    protected $title;

    /**
     * @var BlogContentInterface[]
     *
     * @Assert\Valid()
     */
    protected $contents = [];

    /**
     * @var DateTime
     *
     * @Assert\Type(type="DateTime")
     */
    protected $createdAt;

    /**
     * BlogPostModel constructor.
     */
    public function __construct()
    {
        $this->setCreatedAt($this->getDateTime());
    }

    /**
     * @return array
     */
    public function bsonSerialize(): array
    {
        return [
            '_id' => new ObjectId($this->getId()),
            'slug' => $this->getSlug(),
            'title' => $this->getTitle(),
            'contents' => $this->getContents(),
            'createdAt' => new UTCDateTime($this->getCreatedAt()),
        ];
    }

    /**
     * @param array $data
     */
    public function bsonUnserialize(array $data): void
    {
        /** @var ObjectId $oid */
        $oid = $data['_id'];

        /** @var UTCDateTime $createdAt */
        $createdAt = $data['createdAt'];

        foreach ($data['contents'] as $content) {
            $this->addContent($content);
        }

        $this->setId($oid->__toString());
        $this->setSlug($data['slug']);
        $this->setTitle($data['title']);
        $this->setCreatedAt($createdAt->toDateTime());
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return BlogContentInterface[]
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @param BlogContentInterface[] $contents
     */
    public function setContents($contents): void
    {
        $this->contents = $contents;
    }

    /**
     * @param BlogContentInterface $content
     */
    public function addContent(BlogContentInterface $content): void
    {
        $this->contents[] = $content;
    }

    /**
     * @param BlogContentInterface $content
     */
    public function removeContent(BlogContentInterface $content): void
    {
        $index = array_search($content, $this->contents);
        if ($index !== false) {
            unset($this->contents[$index]);
        }
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    protected function getDateTime(): DateTime
    {
        return new DateTime();
    }
}
