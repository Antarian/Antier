<?php
namespace App\Model;

use DateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class UserModel implements Persistable, UserInterface, EquatableInterface
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string
     *
     * @Groups({"public"})
     */
    protected $username;

    /**
     * @var string
     *
     * @Groups({"public"})
     */
    protected $email;

    /**
     * @var string|null
     */
    protected $password;

    /**
     * @var array
     *
     * @Groups({"public"})
     */
    protected $roles;

    /**
     * @var DateTime
     */
    protected $createdAt;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
    }

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

    public function getRoles(): array
    {
        return ['ROLE_ADMIN'];
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        if ($this->getPassword()) {
            $this->setPassword(null);
        }
    }

    public function isEqualTo(UserInterface $user)
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
    public function setId(string $id = null)
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
    public function setUsername(string $username)
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
    public function setEmail(string $email)
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
    public function setPassword(string $password = null)
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
}