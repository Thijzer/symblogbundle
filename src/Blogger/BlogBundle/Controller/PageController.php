<?php

namespace Blogger\BlogBundle\Controller;

use Blogger\BlogBundle\Entity\Article;
use Blogger\BlogBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;

class PageController extends Controller
{

    public function aboutAction()
    {
        return $this->render('@BloggerBlog/Page/about.html.twig');
    }

    public function contactAction(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(EnquiryType::class, $enquiry);

        $form->handleRequest($request);

        $captcha = $this->get('phpro.captcha-service');

        if(isset($_POST['g-recaptcha-response'])){
            $captcha=$_POST['g-recaptcha-response'];
        }

        if ($form->isSubmitted() && $form->isValid() && $captcha->isValid($request)) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Contact enquiry from symblog')
                ->setFrom('enquiries@symblog.co.uk')
                ->setTo($this->getParameter('blogger_blog.emails.contact_email'))
                ->setBody($this->renderView('@BloggerBlog/Page/contactEmail.txt.twig', array('enquiry' => $enquiry)));
            $this->get('mailer')->send($message);

            $this->addFlash('blogger-notice', 'Your contact enquiry was successfully sent. Thank you!');

            // Redirect - This is important to prevent users re-posting
            // the form if they refresh the page
            return $this->redirectToRoute('blogger_blog_contact');
        }

        return $this->render('@BloggerBlog/Page/contact.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function sidebarAction()
    {
        $articleRepository = $this->getDoctrine()->getRepository(Article::class);
        $tags = $this->createTagList($articleRepository->getTags());
        $tagWeights = $articleRepository->getTagWeights($tags);

        $categories = $this->createCategoryList($articleRepository->getCategories());

        $commentLimit = 10;

        $latestComments = $this
            ->getDoctrine()
            ->getRepository(Comment::class)
            ->getLatestComments($commentLimit)
        ;

        return $this->render('@BloggerBlog/Page/sidebar.html.twig', array(
            'latestComments'    => $latestComments,
            'tags'              => $tagWeights,
            'categories'        => $categories
        ));
    }

    public function createCategoryList($article_categories)
    {
        $categories = array();
        foreach ($article_categories as $article_category)
        {
            $categories = array_merge(explode(",", $article_category['category']), $categories);
        }

        foreach ($categories as &$category)
        {
            $category = trim($category);
        }

        return $categories;
    }

    public function createTagList($blogTags)
    {
        $tags = array();
        foreach ($blogTags as $blogTag)
        {
            $tags = array_merge(explode(",", $blogTag['tags']), $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }

        return $tags;
    }
}