<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BlogPostModel
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Uuid
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
     * @var BlogContentModel[]
     *
     * @Assert\Valid()
     */
    protected $content;

    /**
     * @return string
     */
    public function getId(): string
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
}
