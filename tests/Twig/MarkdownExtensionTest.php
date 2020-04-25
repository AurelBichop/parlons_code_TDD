<?php

namespace App\Tests\Twig;

use App\Service\MarkdownTransformer;
use App\Twig\MarkdownExtension;
use PHPUnit\Framework\TestCase;

class MarkdownExtensionTest extends TestCase
{
    /** @test */
    public function markdownify_should_parse_markdown()
    {
        $str = "Learn **everything** about *Symfony*!";

        $stub = $this->createStub(MarkdownTransformer::class);
        $stub
            ->method('parse')
            ->willReturn("<p>Learn <strong>everything</strong> about <em>Symfony<em>!\n");


        $markDownExtension = new MarkdownExtension($stub);

        $this->assertSame("<p>Learn <strong>everything</strong> about <em>Symfony<em>!\n",$markDownExtension->markdownify($str));
    }

    /** @test */
    public function markdownify_should_delegate_parsing_to_MarkdownTransformer()
    {
        $str = "Learn **everything** about *Symfony*!";

        $mock = $this->createMock(MarkdownTransformer::class);
        $mock
            ->expects($this->once())
            ->method('parse')
            ->with($str);

        $markDownExtension = new MarkdownExtension($mock);

        $markDownExtension->markdownify($str);
    }
}
