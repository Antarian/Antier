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

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }
}
