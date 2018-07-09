<?php
namespace App\Model;

use DateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class UserModel implements Persistable, UserInterface, EquatableInterface
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
     *
     * @Groups({"public"})
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @Groups({"public"})
     */
    protected $email;

    /**
     * @var string|null
     *
     * @Assert\Type(type="string")
     */
    protected $password;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Type(type="string")
     * })
     *
     * @Groups({"public"})
     */
    protected $roles = [];

    /**
     * @var DateTime
     *
     * @Assert\Type(type="DateTime")
     */
    protected $createdAt;

    /**
     * UserModel constructor.
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
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'createdAt' => new UTCDateTime($this->getCreatedAt()),
        ];
    }

    /**
     * @param array $data
     */
    public function bsonUnserialize(array $data)
    {
        /** @var ObjectId $oid */
        $oid = $data['_id'];

        /** @var UTCDateTime $createdAt */
        $createdAt = $data['createdAt'];

        $this->setId($oid->__toString());
        $this->setUsername($data['username']);
        $this->setEmail($data['email']);
        $this->setPassword($data['password']);
        $this->setCreatedAt($createdAt->toDateTime());
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        if ($this->getPassword()) {
            $this->setPassword(null);
        }
    }

    /**
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user): bool
    {
        if (
            !$user instanceof UserModel ||
            $this->getUsername() !== $user->getUsername() ||
            $this->getEmail() !== $user->getEmail()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(string $id = null): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(string $password = null): void
    {
        $this->password = $password;
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
    public function setCreatedAt(DateTime $createdAt)
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