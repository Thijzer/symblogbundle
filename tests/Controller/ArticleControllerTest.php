<?php

namespace Tests\App\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('article.blog')->count() > 0);

        $blogLink   = $crawler->filter('article.blog h2 a')->first();
        $blogTitle  = $blogLink->text();
        $crawler    = $client->click($blogLink->link());

        $this->assertEquals(1, $crawler->filter('h2:contains("' . $blogTitle .'")')->count());
    }

    public function testAddBlogComment()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/page/article/expedita-iusto-4');

        $this->assertEquals(1, $crawler->filter('h2:contains("Expedita iusto. 4")')->count());

        $form = $crawler->selectButton('Submit')->form();

        $client->submit($form, array(
            'comment[user]' => 'name',
            'comment[comment]' => 'comment',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}