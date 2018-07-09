<?php
namespace App\Tests\Command;

use MongoDB;
use MongoDB\Client as MongoClient;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class InstallCommandTest extends TestCase
{

    public function testExecute()
    {
        $command = new Command('antier:install');
        $commandTester = new CommandTester($command);
        //$commandTester->setInputs(['email' => 'test@test.ts', 'password' => '1234567']);
        $commandTester->execute([
         //   'command' => $command->getName(),
            'test@test.ts',
            '1234567',
        ]);
        $this->assertContains('User: ', $commandTester->getDisplay());
    }

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
}