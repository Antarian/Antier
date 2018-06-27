<?php
namespace App\Tests\Controller;

use App\Model\BlogPostModel;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogPostControllerTest extends WebTestCase
{

    /**
     * @dataProvider dataPostBlogPostAction
     *
     * @param $input
     * @param $expected
     */
    public function testPostBlogPostAction_success($input, $expected)
    {
        $client = $this->prepareClient('POST', '/blog/post', $input);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $jsonObj = json_decode($client->getResponse()->getContent());
        $this->assertEquals($expected['slug'], $jsonObj->slug);
    }

    /**
     * @return array
     */
    public function dataPostBlogPostAction()
    {
        return [
            'correctPost' => [
                'input' => json_encode([
                    'slug' => 'abc123',
                    'title' => 'Abc 123',
                ]),
                'expected' => [
                    'slug' => 'abc123',
                    'title' => 'Abc 123',
                    'content' => null
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataGetBlogPostAction
     *
     * @param $input
     */
    public function testGetBlogPostAction_success($input)
    {
        $client = $this->prepareClient('GET', '/blog/post/' . $input);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $jsonArr = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($input, $jsonArr['id']);
        $this->assertArrayHasKey('slug', $jsonArr);
        $this->assertArrayHasKey('title', $jsonArr);
    }

    /**
     * @return array
     */
    public function dataGetBlogPostAction()
    {
        $uuid = Uuid::uuid4();

        return [
            'correctGet' => [
                'input' => $uuid->toString(),
            ],
        ];
    }

    /*** HELPERS ***/

    /**
     * @param string $method
     * @param string|null $uri
     * @param string|null $postData
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
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