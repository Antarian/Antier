<?php
namespace App\Command;

use App\Model\UserModel;
use App\Service\MongoService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class InstallCommand extends Command
{
    protected $passwordEncoder;

    protected $dataService;

    public function __construct(?string $name = null, UserPasswordEncoderInterface $passwordEncoder, MongoService $dataService)
    {
        $this->dataService = $dataService;
        $this->passwordEncoder = $passwordEncoder;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('antier:install')
            ->setDescription('Finalize installation.')
            ->setHelp('This command will guide you through last steps of installation.')
//            ->addArgument('email', InputArgument::REQUIRED, 'The email of admin')
//            ->addArgument('password', InputArgument::REQUIRED, 'Password of admin')
//            ->addArgument('username', $this->requireUsername ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'The username of admin')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        // email
        $emailQuestion = new Question(
            'Please enter login email:',
            ''
        );

        $emailQuestion->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('Email cannot be empty.');
            }

            if (!preg_match( '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/', $value)) {
                throw new \Exception('Unknown email format.');
            }

            return $value;
        });

        // password
        $passwordQuestion = new Question(
            'Please enter login password:',
            ''
        );

        $passwordQuestion->setValidator(function ($value) {
            if (strlen(trim($value)) <= 6) {
                throw new \Exception('Password must be longer than 6 characters.');
            }

            return $value;
        });

        $email = $helper->ask($input, $output, $emailQuestion);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $user = new UserModel();
        $user->setEmail($email);
        $user->setUsername($email);
        $password = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($password);

        $userId = $this->dataService->insertUser($user);

        $output->writeln('User: ' . $userId);
    }
}
