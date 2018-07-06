<?php
namespace App\Tests\Controller;

use App\Model\BlogPostModel;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Client;

class SecurityControllerTest extends ApiTestCase
{

    /**
     * @dataProvider dataRegisterAction
     *
     * @param $input
     * @param $expected
     */
    public function testRegisterAction_success($input, $expected)
    {
        $client = $this->prepareClient('POST', '/register', $input);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());
    }

    /**
     * @return array
     */
    public function dataRegisterAction()
    {
        return [
            'correctPost' => [
                'input' => json_encode([
                    'username' => 'Antarian',
                    'email' => 'antarian@antarianworld.net',
                    'password' => 'abc-123'
                ]),
                'expected' => '{"username":"Antarian","email":"antarian@antarianworld.net","roles":["ROLE_ADMIN"]}',
            ],
        ];
    }

    /**
     * @dataProvider dataAuthAction
     *
     * @param $input
     */
    public function testAuthAction_success($input)
    {
        $client = $this->prepareClient('POST', '/auth', $input);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = $client->getResponse()->getContent();
        $jsonContent = json_decode($content, true);
        $this->assertArrayHasKey('token', $jsonContent);
    }

    /**
     * @return array
     */
    public function dataAuthAction()
    {
        return [
            'correctPost' => [
                'input' => json_encode([
                    'username' => 'Antarian',
                    'password' => 'abc-123'
                ]),
            ],
        ];
    }
}
