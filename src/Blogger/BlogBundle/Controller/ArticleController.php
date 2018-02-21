<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Article;
use Blogger\BlogBundle\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Article controller.
 */
class ArticleController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function indexAction($page = 1)
    {
        $pagination = $this->getArticleRepository()->getAllArticles();

        $adapter = new DoctrineORMAdapter($pagination);
        $pagerfanta = new Pagerfanta($adapter);

        $maxPerPage = $pagerfanta->getMaxPerPage();
        $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default

        $nbResults = $pagerfanta->getNbResults();

        $pagerfanta->getNbPages();

       $pagerfanta->setCurrentPage($page);

        $pagerfanta->haveToPaginate($nbResults); // whether the number of results is higher than the max per page

        return $this->render('@BloggerBlog/Page/index.html.twig', [
            'my_pager' => $pagerfanta,
        ]);
    }

    /**
     * Show a article entry
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($slug)
    {
        $article = $this->getArticleRepository()->findBySlug($slug);

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