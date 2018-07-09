<?php
namespace App\Model;

use MongoDB\BSON\Persistable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BlogContentTextModel
 *
 * nested object for BlogPostModel class containing HTML text
 *
 * @package App\Model
 */
class BlogContentTextModel implements Persistable, BlogContentInterface
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $type = 'text';

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $text;

    /**
     * @return array
     */
    public function bsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'text' => $this->getText(),
        ];
    }

    /**
     * @param array $data
     */
    public function bsonUnserialize(array $data): void
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
    public function setText(string $text): void
    {
        $this->text = $text;
    }
}
