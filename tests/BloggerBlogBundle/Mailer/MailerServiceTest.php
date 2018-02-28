<?php

namespace Tests\BloggerBlogBundle\Mailer;

use App\Mailer\EmailAddress;
use App\Mailer\Mail;
use App\Mailer\MailerService;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class MailerServiceTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Swift_Mailer */
    private $mailer;
    /** @var \Twig_Environment */
    private $twig;
    /** @var MailerService */
    private $mailservice;

    public function setUp()
    {
        $this->twig = $this->createTwigEnvironment('text');
        $this->mailer = $this->createSwiftMailer('text');

        $this->mailservice = new MailerService(
            $this->twig->reveal(),
            $this->mailer->reveal()
        );
    }

    public function testMail()
    {
        $mail = new Mail(
            'subject',
            EmailAddress::createEmailAddress('john@mail.com'),
            EmailAddress::createEmailAddress('jay@mail.com'),
            'body'
        );

        $this->mailer->send(Argument::type(\Swift_Message::class))->shouldBeCalled();

        $this->mailservice->sendMail($mail);
    }

    public function testTwigTemplate()
    {
        $this->assertEquals('text', $this->mailservice->renderTemplate('name', []));
    }

    /**
     * @return \Swift_Mailer|ObjectProphecy
     */
    private function createSwiftMailer()
    {
        /** @var \Swift_Mailer $mailer */
        return $this->prophesize(\Swift_Mailer::class);
    }

    /**
     * @return \Twig_Environment|ObjectProphecy
     */
    private function createTwigEnvironment($render)
    {
        /** @var \Twig_Environment $twig */
        $twig = $this->prophesize(\Twig_Environment::class);
        $twig->render(Argument::type('string'), Argument::type('array'))->willReturn($render);

        return $twig;
    }
}
