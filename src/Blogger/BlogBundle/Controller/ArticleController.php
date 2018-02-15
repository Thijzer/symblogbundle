<?php
/**
 * Created by PhpStorm.
 * User: phpro
 * Date: 15/02/2018
 * Time: 10:32
 */

namespace Blogger\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Article controller.
 */
class ArticleController extends Controller
{
    /**
     * Show a article entry
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $article = $this->getDoctrine()->getRepository('Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find article post.');
        }

        return $this->render('BloggerBlogBundle:Article:show.html.twig', array(
            'article'      => $article,
        ));
    }
}