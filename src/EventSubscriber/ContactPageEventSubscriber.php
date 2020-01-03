<?php

namespace App\EventSubscriber;

use App\Event\EnquiryEvent;
use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Mailer\MailerService;

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
                $this->mailerService->renderTemplate('@BloggerBlog/Page/contactEmail.txt.twig', [
                    'enquiry' => $enquiry,
                ])
            )
        );
    }
}
