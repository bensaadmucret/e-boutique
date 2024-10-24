<?php

namespace App\Infrastructure\Twig;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('alert')]
class Alert
{
    public string $type = 'success';
    public string $message;

    public function getIcon(): string
    {
        return match ($this->type) {
            'success' => 'bi:check-circle',
            'danger' => 'bi:exclamation-circle',
        };
    }
}
