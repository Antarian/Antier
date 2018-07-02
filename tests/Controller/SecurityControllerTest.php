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
     * @param $expected
     */
    public function testAuthAction_success($input, $expected)
    {
        $client = $this->prepareClient('POST', '/auth', $input);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());
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
                'expected' => '{"token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzUxMiJ9.eyJpYXQiOjE1MzA0OTE0MzMsImV4cCI6MTUzMDQ5NTAzMywicm9sZXMiOlsiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6IkFudGFyaWFuIn0.3a9_N1cLBypdPCmZHJyVXCHZ6IVgJyBoIT3fumjvK7jbJ-LffYbpZXQI33PRAAoduDeBfNBskgjAUcwiT0oKeavon6bQC5erDLP_m0ennIEPYfS25Gxj5Xc00qMokCvcytx7zBnoVg_MlEmX9fLKjf3BrYsD4dfv3BvHfACF2jH9lEknsJn_TB-j0g5-5hiFVKBZxxChK4h6meVoaKeQvEAc720SNraCD3huXrtFJHprZF7_P6ilYYljUu_hjc8WBYjzdHRua3a64Qp2_IfhFLKcgwRJtjkyyb6EwP5mxMowNTTTCmLAbCP0hx9KCu8Oz3XW1pyeUTZKgZwBCV2EJc1KJO1fwmNpf_mVx2OBwvIhyBV5iyVZkm_bRWsbv6oByKg_J2kjDVt2beVAy60SajXo0zGsZSRZ34Wofgg2RW25APkp13bBIVSPPUA1Kj99K7N24BfObe-jIrIxH88gI4hzGNAY966pr2FXQw0OMh1kXxJ4KYAyIIZD47FmiZqqRwEOUF6V1FplQr1gDMA90yLF9YKohsPfkFf58VXBqZxtatX_ubc6pif0qEcOrdpwLtxRhNw5XAe6aXeQainPxQ0II1Hp5m4e-9LSt5RGYuFL4NI3XgdewWUORhCZlgZx4jcIClFw39iKPM89wNguwA9LgHXc8W8PD0oK3or4IvM"}',
            ],
        ];
    }
}
