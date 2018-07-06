<?php
namespace App\Model;

use MongoDB\BSON\Persistable;
use stdClass;
use Symfony\Component\Validator\Constraints as Assert;

class BlogTextModel implements Persistable, BlogContentInterface
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

    public function __construct(stdClass $data)
    {
        $this->type = 'text';
        $this->setText($data->text);
    }

    public function bsonSerialize()
    {
        return [
            'type' => $this->getType(),
            'text' => $this->getText(),
        ];
    }


    public function bsonUnserialize(array $data)
    {
        $this->setText($data['text']);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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
