<?php
namespace App\Controller;

use App\Model\BlogPostModel;
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
class BlogPostController extends Controller
{
    protected $validator;

    protected $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
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
        return new Response($slug);
    }

    /**
     * @Route("/post", methods={"POST"}, name="create_blog_post")
     *
     * @param Request $request
     * @return Response
     */
    public function postBlogPostAction(Request $request)
    {
        $data = $request->request->all();
        $post = $this->serializer->deserialize($data, BlogPostModel::class, 'json');
        $errors = $this->validator->validate($post);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        return new Response($post);
    }

    /**
     * @Route("/post/{slug}", methods={"PUT"}, name="update_blog_post")
     *
     * @param $slug
     */
    public function putBlogPostAction($slug)
    {

    }
}
