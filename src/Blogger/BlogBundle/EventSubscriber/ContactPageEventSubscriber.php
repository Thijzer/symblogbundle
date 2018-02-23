<?php

namespace Blogger\BlogBundle\EventSubscriber;


class ContactPageEventSubscriber
{
    public function onCustomEvent($event)
    {
        echo 'Enquiry was created';

        $message = \Swift_Message::newInstance()
            ->setSubject('Contact enquiry from symblog')
            ->setFrom('enquiries@symblog.co.uk')
            ->setTo($this->getParameter('blogger_blog.emails.contact_email'))
            ->setBody($this->renderView('@BloggerBlog/Page/contactEmail.txt.twig', array('enquiry' => $enquiry)));
        $this->get('mailer')->send($message);
    }
}