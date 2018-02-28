<?php

namespace spec\Blogger\BlogBundle\Mailer;

use Blogger\BlogBundle\Mailer\EmailAddress;
use PhpSpec\ObjectBehavior;

/**
 * @mixin EmailAddress
 */
class EmailAddressSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('john@mail.com', 'John Doe');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EmailAddress::class);
    }

    function it_should_return_a_email()
    {
        $this->getEmail()->shouldReturn('john@mail.com');
    }

    function it_should_return_a_name()
    {
        $this->getName()->shouldReturn('John Doe');
    }
}