<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BlogPostModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
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
     * @var BlogContentModel[]
     */
    protected $content;
}
