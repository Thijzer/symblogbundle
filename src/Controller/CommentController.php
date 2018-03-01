<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Comment;
use App\Form\CommentType;
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
    public function create(string $articleId, Request $request)
    {
        $comment = new Comment($this->getArticle($articleId));

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getCommentRepository()->addComment($comment);
        }

        return $this->render('@BloggerBlog/Comment/create.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * @return \App\Repository\CommentRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function getCommentRepository()
    {
        return $this->getDoctrine()->getRepository(Comment::class);
    }

    /**
     * @param $articleId
     * @return Article
     */
    private function getArticle(string $articleId)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($articleId);

        if (null === $article) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $article;
    }
}