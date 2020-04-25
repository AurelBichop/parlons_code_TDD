<?php

namespace App\Twig;

use Twig\TwigFilter;
use App\Service\MarkdownTransformer;
use Twig\Extension\AbstractExtension;

class MarkdownExtension extends AbstractExtension
{
    private $markdownTransformer;

    public function __construct(MarkdownTransformer $markdownTransformer)
    {
        $this->markdownTransformer = $markdownTransformer;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('markdownify', [$this, 'parseMarkdown'], ['is_safe' => ['html']]),
        ];
    }

    public function parseMarkdown(string $value): string
    {
        return $this->markdownTransformer->parse($value);
    }
}
