<?php
namespace App\Tests\Controller;

use App\Model\BlogPostModel;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Client;

class BlogPostControllerTest extends ApiTestCase
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
}