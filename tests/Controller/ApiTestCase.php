<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use MongoDB;
use MongoDB\Client as MongoClient;

class ApiTestCase extends WebTestCase
{
    /**
     * Clear DB before every test
     */
    public static function tearDownAfterClass()
    {
        $user = 'antieruser';
        $password = 'antierpass';
        $database = 'antierdb';

        /** @var MongoDB\Database */
        $db = (new MongoClient('mongodb://antierdata/', ['username' => $user, 'password' => $password]))->{$database};
        $db->drop();
    }

    /**
     * @param string $method
     * @param string|null $uri
     * @param string|null $postData
     *
     * @return Client
     */
    protected function prepareClient(string $method = 'GET', string $uri = null, string $postData = null): Client
    {
        $client = static::createClient();

        $headers = [];
        $headers['HTTP_ACCEPT'] = 'application/json';
        if (in_array($method, ['POST', 'PUT'])) {
            $headers['CONTENT_TYPE'] = 'application/json';
        }

        $client->request(
            $method,
            $uri,
            [],
            [],
            $headers,
            $postData
        );

        return $client;
    }
}