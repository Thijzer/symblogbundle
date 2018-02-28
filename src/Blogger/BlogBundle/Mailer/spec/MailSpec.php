<?php

namespace spec\Blogger\BlogBundle\Mailer;

use Blogger\BlogBundle\Mailer\EmailAddress;
use Blogger\BlogBundle\Mailer\Mail;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Mail
 */
class MailSpec extends ObjectBehavior
{
    function let(EmailAddress $emailAddress)
    {
        $this->beConstructedWith(
            'subject',
            $emailAddress,
            clone $emailAddress,
            'body'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Mail::class);
    }

    function it_should_return_a_subject()
    {
        $this->getSubject()->shouldReturn('subject');
    }

    function it_should_return_a_body()
    {
        $this->getBody()->shouldReturn('body');
    }

    function it_should_return_a_sender()
    {
        $this->getReceiver()->shouldHaveType(EmailAddress::class);
    }

    function it_should_return_a_receiver()
    {
        $this->getSender()->shouldHaveType(EmailAddress::class);
    }
}