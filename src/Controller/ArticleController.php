<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
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
     *
     * @throws \LogicException
     */
    public function index($page = 1)
    {
        $articles = $this->getArticleRepository()->getAllArticles();

        $pagerfanta = $this->pagination($page, $articles);

        return $this->render('Page/index.html.twig', [
            'my_pager' => $pagerfanta,
        ]);
    }

    public function showByCategory($category, $page = 1)
    {
        $articles = $this->getArticleRepository()->getAllArticlesByCategory($category);

        $pagerfanta = $this->pagination($page, $articles);

        return $this->render('@BloggerBlog/Page/index.html.twig', [
                'my_pager'      => $pagerfanta,
        ]);
    }

    /**
     * Show a article entry
     *
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \LogicException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show($slug)
    {
        $article = $this->getArticleRepository()->findBySlug($slug);

        if (!$article) {
            throw $this->createNotFoundException('Unable to find article post.');
        }

        $comments = $this->getDoctrine()->getRepository('App:Comment')
            ->getCommentsForBlog($article->getId());

        return $this->render('@BloggerBlog/Article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository | ArticleRepository
     *
     * @throws \LogicException
     */
    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository(Article::class);
    }

    private function pagination($page, $articles)
    {
        $adapter = new DoctrineORMAdapter($articles);
        $pagerfanta = new Pagerfanta($adapter);

        $maxPerPage = $pagerfanta->getMaxPerPage();
        $pagerfanta->setMaxPerPage($maxPerPage); // 10 by default

        $nbResults = $pagerfanta->getNbResults();
        $pagerfanta->getNbPages();

        $pagerfanta->setCurrentPage($page);
        $pagerfanta->haveToPaginate($nbResults); // whether the number of results is higher than the max per page

        return $pagerfanta;
    }
}
