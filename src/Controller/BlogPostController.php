<?php
namespace App\Controller;

use App\Model\BlogPostModel;
use App\Model\BlogContentTextModel;
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

    protected $dataService;

    /**
     * BlogPostController constructor.
     *
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     */
    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, MongoService $dataService)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->dataService = $dataService;
    }

    /**
     * @Route("/post/{slug}", methods={"GET"}, name="read_blog_post")
     *
     * @param $slug
     *
     * @return Response
     */
    public function getBlogPostAction($slug)
    {
        $blogPost = $this->dataService->findBlogPostBySlug($slug);
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
        $blogPost = $this->serializer->deserialize($content, BlogPostModel::class, 'json', ['allow_extra_attributes' => true]);
        $errors = $this->validator->validate($blogPost);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $oid = $this->dataService->insertBlogPost($blogPost);
        $blogPostObj = $this->dataService->findBlogPostBySlug($blogPost->getSlug());

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
