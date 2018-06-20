<?php
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BlogContentModel
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $type;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $text;
}
