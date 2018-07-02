<?php
namespace App\Service;

use App\Model\BlogPostModel;
use App\Model\UserModel;
use MongoDB;
use MongoDB\BSON\ObjectId;
use MongoDB\Client;

class MongoService
{
    /** @var MongoDB\Database */
    protected $db;

    public function __construct(string $user = 'antieruser', string $password = 'antierpass', string $database = 'antierdb')
    {
        $this->db = (new Client('mongodb://antierdata/', ['username' => $user, 'password' => $password]))->{$database};
    }

    public function insertUser(UserModel $user)
    {
        $collection = $this->db->users;

        $collection->createIndexes([
            [ 'key' => [ 'username' => 1 ], 'unique' => true ],
            [ 'key' => [ 'email' => 1 ], 'unique' => true ],
        ]);

        $insertUserResult = $collection->insertOne($user);

        return $insertUserResult->getInsertedId();
    }

    public function findUserByUsername(string $username)
    {
        $collection = $this->db->users;

        $findOneResult = $collection->findOne(['username' => $username]);

        return $findOneResult;
    }

    public function insertBlogPost(BlogPostModel $blogPost)
    {
        $collection = $this->db->blogs;

        $collection->createIndexes([
            [ 'key' => [ 'slug' => 1 ], 'unique' => true ],
        ]);

        $insertOneResult = $collection->insertOne($blogPost);

        return $insertOneResult->getInsertedId();
    }

    public function findBlogPostBySlug(string $slug)
    {
        $collection = $this->db->blogs;

        $findOneResult = $collection->findOne(['slug' => $slug]);

        return $findOneResult;
    }
}
