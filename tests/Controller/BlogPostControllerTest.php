<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogPostControllerTest extends WebTestCase
{
    public function testPostBlogPostAction()
    {
        $client = static::createClient();

        $postData = [
            'service' => 'test',
        ];

        $client->request('POST', '/blog/post', $postData);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals("{}", $client->getResponse()->getContent());
    }
}