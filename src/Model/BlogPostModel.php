<?php
namespace App\Model;

use MongoDB\BSON\Unserializable;
use Symfony\Component\Validator\Constraints as Assert;

class BlogPostModel implements Unserializable
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $oid;

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
     * @var BlogContentModel[]
     *
     * @Assert\Valid()
     */
    protected $content;

    /**
     * @return string
     */
    public function getOid(): string
    {
        return $this->oid;
    }

    /**
     * @param string $oid
     */
    public function setOid(string $oid)
    {
        $this->oid = $oid;
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
    public function setSlug(string $slug)
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
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return BlogContentModel[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param BlogContentModel[] $content
     */
    public function setContent(array $content)
    {
        $this->content = $content;
    }

    /**
     * @todo symfony serializer to/from bson
     *
     * @param array $data
     */
    public function bsonUnserialize(array $data)
    {
        foreach ( $data as $k => $value )
        {
            $this->$k = $value;
        }
    }
}
