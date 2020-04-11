<?php

namespace App\Twig;

use Twig\TwigFilter;
use App\Entity\Event;
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
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('format_price', [$this, 'formatPrice'], ['is_safe' => ['html']]),
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }

    public function formatPrice(Event $event)
    {
        return $event->isFree() ? '<strong>FREE!</strong>' : '$' . $event->getPrice();
    }

    public function pluralize($count, string $singular, string $plural = null)
    {
       
        if (!is_numeric($count)) {
            throw new \InvalidArgumentException("{$count} must be a numeric value");
        }

        $plural = $plural ?? $singular . 's';

        $string = $count == 1 ? $singular : $plural;

        return "{$count} {$string}";
    }
}
