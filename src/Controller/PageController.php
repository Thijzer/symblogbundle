<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Event\EnquiryEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Enquiry;
use App\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;
use App\Extractor\TagExtractor;

class PageController extends Controller
{
    public function about()
    {
        return $this->render('Page/about.html.twig');
    }

    public function contact(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry);

        $form->handleRequest($request);

        $captcha = $this->get('phpro.captcha-service');

        if ($form->isSubmitted() && $form->isValid() && $captcha->isValid($request)) {

            $event = new EnquiryEvent($enquiry);
            $this->get('event_dispatcher')->dispatch('custom.event.contact_page', $event);

            $this->addFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

            return $this->redirectToRoute('page_contact');
        }

        return $this->render('Page/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function sidebar()
    {
        $articleRepository  = $this->getDoctrine()->getRepository(Article::class);
        $categoryRepository = $this->getDoctrine()->getRepository(Category::class);

        $categories = $this->createCategoryList($categoryRepository->getCategories()->getArrayResult());

        $latestComments = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->getLatestComments($commentLimit = 10)
        ;

        return $this->render('Page/sidebar.html.twig', [
            'latestComments'    => $latestComments,
            'tags'              => (new TagExtractor())->getTagWeights(
                $articleRepository->getAllArticles()->getResult()
            ),
            'categories'        => $categories,
        ]);
    }

    public function createCategoryList($articleCategories)
    {
        $categories = [];
        foreach ($articleCategories as $article_category) {
            $categories = array_merge(explode(",", $article_category['category']), $categories);
        }

        foreach ($categories as &$category) {
            $category = trim($category);
        }

        return $categories;
    }
}
