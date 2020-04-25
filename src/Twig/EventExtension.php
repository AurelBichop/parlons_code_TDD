<?php

namespace App\Twig;

use App\Entity\Event;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class EventExtension extends AbstractExtension
{

    public function getFunctions(): array
    {
        return [
            new TwigFunction('format_price', [$this, 'formatPrice'], ['is_safe' => ['html']]),
        ];
    }

    public function formatPrice(Event $event)
    {
        return $event->isFree() ? '<strong>FREE!</strong>' : '$' . $event->getPrice();
    }
}
