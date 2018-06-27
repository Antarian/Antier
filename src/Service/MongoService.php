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

        $insertOneResult = $collection->insertOne($document);

        return $insertOneResult->getInsertedId();
    }

    public function findDocument($id)
    {
        $collection = (new Client('mongodb://antieruser:antierpass@antierdata/'))->antierdb->blogs;

        $findOneResult = $collection->findOne(['_id' => new ObjectId($id)]);

        return $findOneResult;
    }
}
