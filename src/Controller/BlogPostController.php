<?php
namespace App\Controller;

use App\Model\BlogPostModel;
use App\Service\MongoService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BlogPostController
 * @package App\Controller
 *
 * @Route("/blog", defaults={"_format": "json"})
 */
class BlogPostController extends Controller implements PublicApiInterface
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var SerializerInterface */
    protected $serializer;

    protected $service;

    /**
     * BlogPostController constructor.
     *
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, MongoService $service)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->service = $service;
    }

    /**
     * @Route("/post/{id}", methods={"GET"}, name="read_blog_post")
     *
     * @param $id
     *
     * @return Response
     */
    public function getBlogPostAction($id)
    {
        $blogPost = new BlogPostModel();
        $blogPost->setId($id);
        $blogPost->setSlug('slug');
        $blogPost->setTitle('title');

        $errors = $this->validator->validate($blogPost);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $blogPostJson = $this->serializer->serialize($blogPost, 'json');
        return new Response($blogPostJson);
    }

    /**
     * @Route("/post", methods={"POST"}, name="create_blog_post")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postBlogPostAction(Request $request)
    {
        $content = $request->getContent();
        /** @var BlogPostModel $blogPost */
        $blogPost = $this->serializer->deserialize($content, BlogPostModel::class, 'json');
        $errors = $this->validator->validate($blogPost);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $oid = $this->service->insertBlogPost($blogPost);
        $blogPostObj = $this->service->findBlogPostBySlug($blogPost->getSlug());

        $blogPostJson = $this->serializer->serialize($blogPostObj, 'json');
        return new Response($blogPostJson);
    }

    /**
     * @Route("/post/{id}", methods={"PUT"}, name="update_blog_post")
     *
     * @param string $id
     * @param Request $request
     *
     * @return Response
     */
    public function putBlogPostAction(Request $request, $oid)
    {
        $content = $request->getContent();
        /** @var BlogPostModel $blogPost */
        $blogPost = $this->serializer->deserialize($content, BlogPostModel::class, 'json');
        $errors = $this->validator->validate($blogPost);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $blogPost->setId($oid);

        $blogPostJson = $this->serializer->serialize($blogPost, 'json');
        return new Response($blogPostJson);
    }
}
