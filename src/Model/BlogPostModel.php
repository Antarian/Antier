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
     * @var string
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
     * @var BlogTextModel[]
     *
     * @Assert\Valid()
     */
    protected $content;

    /**
     * @var DateTime
     *
     * @Assert\Type(type="\DateTime")
     */
    protected $createdAt;

    public function __construct()
    {
        //$this->content = [];
        $this->setCreatedAt(new DateTime());
    }


    public function bsonSerialize()
    {
        return [
            '_id' => new ObjectId($this->getId()),
            'slug' => $this->getSlug(),
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'createdAt' => new UTCDateTime($this->getCreatedAt()),
        ];
    }


    public function bsonUnserialize(array $data)
    {
        /** @var ObjectId $oid */
        $oid = $data['_id'];

        /** @var UTCDateTime $createdAt */
        $createdAt = $data['createdAt'];

        foreach ($data['content'] as $contentItem) {
            $this->addContentItem($contentItem);
        }

        $this->setId($oid->__toString());
        $this->setSlug($data['slug']);
        $this->setTitle($data['title']);
        $this->setCreatedAt($createdAt->toDateTime());
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return BlogTextModel[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param BlogTextModel[] $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param BlogContentInterface $contentItem
     */
    public function addContentItem(BlogContentInterface $contentItem)
    {
        $this->content[] = $contentItem;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    protected function getDateTime()
    {
        return new DateTime();
    }
}
