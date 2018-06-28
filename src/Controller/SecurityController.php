<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class SecurityController
 * @package App\Controller
 *
 * @Route("/", defaults={"_format": "json"})
 */
class SecurityController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/login", methods={"POST"}, name="login")
     */
    public function loginAction(Request $request)
    {
    }
}