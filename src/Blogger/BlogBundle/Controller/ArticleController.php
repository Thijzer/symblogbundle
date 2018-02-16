<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Article;
use Blogger\BlogBundle\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Article controller.
 */
class ArticleController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function indexAction()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->getLatestArticles();

        return $this->render('@BloggerBlog/Page/index.html.twig', array(
            'articles' => $articles
        ));
    }

    /**
     * Show a article entry
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $article = $this->getDoctrine()->getRepository('BloggerBlogBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find article post.');
        }

        $comments = $this->getDoctrine()->getRepository('BloggerBlogBundle:Comment')
            ->getCommentsForBlog($article->getId());

        return $this->render('@BloggerBlog/Article/show.html.twig', array(
            'article'      => $article,
            'comments'  => $comments
        ));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository | ArticleRepository
     * @throws \LogicException
     */
    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }
}