<?php


namespace Tests\BloggerBlogBundle\Twig\Extensions;

use App\Twig\Extensions\BloggerBlogExtension;

class BloggerBlogExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreatedAgo()
    {
        $bloggerBlogExtension = new BloggerBlogExtension();

        $this->assertEquals("34 seconds ago", $bloggerBlogExtension->createdAgo($this->getDateTime(-34)));
        $this->assertEquals("1 minute ago", $bloggerBlogExtension->createdAgo($this->getDateTime(-60)));
        $this->assertEquals("2 minutes ago", $bloggerBlogExtension->createdAgo($this->getDateTime(-120)));
        $this->assertEquals("1 hour ago", $bloggerBlogExtension->createdAgo($this->getDateTime(-3600)));
        $this->assertEquals("1 hour ago", $bloggerBlogExtension->createdAgo($this->getDateTime(-3601)));
        $this->assertEquals("2 hours ago", $bloggerBlogExtension->createdAgo($this->getDateTime(-7200)));

        // Cannot create time in the future
        $this->setExpectedException('\Exception');
        $bloggerBlogExtension->createdAgo($this->getDateTime(60));
    }

    protected function getDateTime($delta)
    {
        return new \DateTime(date("Y-m-d H:i:s", time()+$delta));
    }
}
