<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\SwiftmailerBundle\DataCollector\MessageDataCollector;

class PageControllerTest extends WebTestCase
{
    public function testAbout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/page/about');

        $this->assertEquals(1, $crawler->filter('h1:contains("About symblog")')->count());
    }

    public function testContact()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/page/contact');

        $this->assertEquals(1, $crawler->filter('h1:contains("Contact symblog")')->count());

        $form = $crawler->selectButton('Submit')->form();

        $form['enquiry[name]'] = 'name';
        $form['enquiry[email]'] = 'email@email.com';
        $form['enquiry[subject]'] = 'Subject';
        $form['enquiry[body]'] = 'The comment body must be at least 50 characters long as there is a validation constrain on the Enquiry entity';

        $client->submit($form);

        if ($profile = $client->getProfile()) {
            /** @var $swiftMailerProfiler MessageDataCollector */
            $swiftMailerProfiler = $profile->getCollector('swiftmailer');

            $this->assertEquals(1, $swiftMailerProfiler->getMessageCount());

            $messages = $swiftMailerProfiler->getMessages();
            /** @var \Swift_Message $message */
            $message = current($messages);

            $symblogEmail = $client->getContainer()->getParameter('blogger_blog.emails.contact_email');

            $this->assertArrayHasKey($symblogEmail, $message->getTo());
        }

        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.blogger-notice:contains("Your contact enquiry was successfully sent. Thank you!")')->count() > 0);
    }
}