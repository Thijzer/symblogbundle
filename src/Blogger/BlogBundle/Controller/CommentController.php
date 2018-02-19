<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Comment;
use Blogger\BlogBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    /**
     * @param $articleId
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction($articleId, Request $request)
    {
        $comment = new Comment($this->getArticle($articleId));
        $article = new Article($this->getArticle($articleId));

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommentRepository()->addComment($comment);

            return $this->redirect('blogger_blog_show', [
                'id'    => $article->getId(),
                'slug'  => $article->getSlug(),
                'comment' => $comment->getId(),
            ]);
        }

        return $this->render('@BloggerBlog/Comment/create.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return \Blogger\BlogBundle\Repository\CommentRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getCommentRepository()
    {
        return $this->getDoctrine()->getRepository(Comment::class);
    }

    /**
     * @param $articleId
     * @return Article
     */
    protected function getArticle($articleId)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);

        if (null === $article) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $article;
    }
}