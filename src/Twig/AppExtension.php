<?php

namespace App\Twig;

use Twig\TwigFilter;
use Cake\Utility\Text;
use Twig\TwigFunction;

use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('truncate', [$this, 'truncate'], ['is_safe' => ['html']]),
        ];
    }

    public function truncate (string $text, int $length = 100):string{
        return Text::truncate($text, $length, [
            'ellipsis' => '...',
            'exact' => true,
            'html' => true
        ]);
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }


    public function pluralize($count, string $singular, ?string $plural = null)
    {
       
        if (!is_numeric($count)) {
            throw new \InvalidArgumentException("{$count} must be a numeric value");
        }

        $plural = $plural ?? $singular . 's';

        $string = $count == 1 ? $singular : $plural;

        return "{$count} {$string}";
    }
}
