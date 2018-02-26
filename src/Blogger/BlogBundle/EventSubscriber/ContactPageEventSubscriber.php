<?php

namespace Blogger\BlogBundle\EventSubscriber;

use Blogger\BlogBundle\Event\EnquiryEvent;
use Blogger\BlogBundle\Mailer\EmailAddress;
use Blogger\BlogBundle\Mailer\Mail;
use Blogger\BlogBundle\Mailer\MailerService;

class ContactPageEventSubscriber
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public function onCustomEvent(EnquiryEvent $event)
    {
        $enquiry = $event->getCode();

        $this->mailerService->sendMail(
            new Mail(
                'Contact enquiry from symblog',
                EmailAddress::createEmailAddress('username@domain.tld'),
                EmailAddress::createEmailAddress('jonas.degauquier@gmail.com'),
                $this->mailerService->renderTemplate('@BloggerBlog/Page/contactEmail.txt.twig' , [
                    'enquiry' => $enquiry,
                ])
            )
        );
    }
}