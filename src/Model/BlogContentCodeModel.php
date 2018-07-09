<?php
namespace App\Model;

use MongoDB\BSON\Persistable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BlogContentCodeModel
 *
 * nested object for BlogPostModel class containing HTML text
 *
 * @package App\Model
 */
class BlogContentCodeModel implements Persistable, BlogContentInterface
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $type = 'code';

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     */
    protected $code;

    /**
     * @return array
     */
    public function bsonSerialize(): array
    {
        return [
            'type' => $this->getType(),
            'code' => $this->getCode(),
        ];
    }

    /**
     * @param array $data
     */
    public function bsonUnserialize(array $data): void
    {
        $this->setCode($data['code']);
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }
}
