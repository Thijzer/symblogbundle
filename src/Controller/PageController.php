<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Event\EnquiryEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Enquiry;
use App\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{
    public function about()
    {
        return $this->render('@BloggerBlog/Page/about.html.twig');
    }

    public function contact(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry);

        $form->handleRequest($request);

        $captcha = $this->get('phpro.captcha-service');

        if ($form->isSubmitted() && $form->isValid() && $captcha->isValid($request)) {
            $eventDispatcher = $this->get('event_dispatcher');
            $event = new EnquiryEvent($enquiry);
            $eventDispatcher->dispatch('custom.event.contact_page', $event);

            $this->addFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');
            // Redirect - This is important to prevent users re-posting
            // the form if they refresh the page
            return $this->redirectToRoute('blogger_blog_contact');
        }

        return $this->render('@BloggerBlog/Page/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function sidebar()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);

        $categories = $this->createCategoryList($articleRepository->getCategories());

        $latestComments = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->getLatestComments($commentLimit = 10)
        ;

        return $this->render('@BloggerBlog/Page/sidebar.html.twig', [
            'latestComments'    => $latestComments,
            'tags'              => $this->get('phpro.extractor.tag_extractor')->getTagWeights(
                $articleRepository->getAllArticles()->getResult()
            ),
            'categories'        => $categories,
        ]);
    }

    public function createCategoryList($article_categories)
    {
        $categories = [];
        foreach ($article_categories as $article_category) {
            $categories = array_merge(explode(",", $article_category['category']), $categories);
        }

        foreach ($categories as &$category) {
            $category = trim($category);
        }

        return $categories;
    }
}
