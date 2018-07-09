<?php
namespace App\Controller;

use App\Model\UserModel;
use App\Service\MongoService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class SecurityController
 * @package App\Controller
 *
 * @Route("/", defaults={"_format": "json"})
 */
class SecurityController extends Controller
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var SerializerInterface */
    protected $serializer;

    /** @var MongoService */
    protected $dataService;

    /** @var UserPasswordEncoderInterface */
    protected $passwordEncoder;

    /** @var JWTEncoderInterface */
    protected $tokenEncoder;

    /**
     * SecurityController constructor.
     *
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param MongoService $dataService
     * @param UserPasswordEncoderInterface $passwordEncoder\
     */
    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, MongoService $dataService, UserPasswordEncoderInterface $passwordEncoder, JWTEncoderInterface $tokenEncoder)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->dataService = $dataService;
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenEncoder = $tokenEncoder;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/register", methods={"POST"}, name="register")
     *
     */
    public function registerAction(Request $request)
    {
        $content = $request->getContent();
        /** @var UserModel $user */
        $user = $this->serializer->deserialize($content, UserModel::class, 'json');
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $oid = $this->dataService->insertUser($user);
        $userObj = $this->dataService->findUserByUsername($user->getUsername());

        $userJson = $this->serializer->serialize($userObj, 'json', ['groups' => ['public']]);
        return new Response($userJson);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws JWTEncodeFailureException
     *
     * @Route("/auth", methods={"POST"}, name="auth")
     */
    public function authAction(Request $request)
    {
        $content = $request->getContent();
        /** @var UserModel $user */
        $user = $this->serializer->deserialize($content, UserModel::class, 'json');
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        // check password
        $user->eraseCredentials();

        $user = $this->dataService->findUserByUsername($user->getUsername());
        $token = $this->tokenEncoder->encode($user);
        $tokenJson = json_encode(['token' => $token]);

        return new Response($tokenJson);
    }
}
