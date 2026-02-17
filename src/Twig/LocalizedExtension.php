<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LocalizedExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('localized', [$this, 'localized']),
        ];
    }

    public function localized(object $entity, string $fieldBase, string $locale, ?string $fallbackLocale = 'en'): ?string
    {
        $locale = strtolower($locale);
        $primary = $this->readValue($entity, $fieldBase.$this->localeSuffix($locale));
        if ($primary !== null && $primary !== '') {
            return $primary;
        }

        if ($fallbackLocale && $fallbackLocale !== $locale) {
            return $this->readValue($entity, $fieldBase.$this->localeSuffix($fallbackLocale));
        }

        return $primary;
    }

    private function localeSuffix(string $locale): string
    {
        return ucfirst(substr($locale, 0, 2));
    }

    private function readValue(object $entity, string $property): ?string
    {
        $getter = 'get'.ucfirst($property);
        if (method_exists($entity, $getter)) {
            $value = $entity->{$getter}();
            return is_string($value) ? $value : null;
        }

        return null;
    }
}
