<?php
namespace App\Service;

use MongoDB;
use MongoDB\BSON\ObjectId;
use MongoDB\Client;

class MongoService
{
    protected $client;

    public function __construct()
    {

    }

    public function insertDocument($document)
    {
        $collection = (new Client('mongodb://antieruser:antierpass@antierdata/'))->antierdb->blogs;

        $insertOneResult = $collection->insertOne([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        return $insertOneResult->getInsertedId();
    }

    public function findDocument($oid)
    {
        $collection = (new Client('mongodb://antieruser:antierpass@antierdata/'))->antierdb->blogs;

        $findOneResult = $collection->findOne(['_id' => new ObjectId($oid)]);

        return $findOneResult;
    }
}
