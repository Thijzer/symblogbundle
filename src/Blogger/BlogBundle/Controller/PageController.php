<?php
/**
 * Created by PhpStorm.
 * User: phpro
 * Date: 14/02/2018
 * Time: 10:16
 */

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('@BloggerBlog/Page/index.html.twig');
    }

    public function aboutAction()
    {
        return $this->render('@BloggerBlog/Page/about.html.twig');
    }
}