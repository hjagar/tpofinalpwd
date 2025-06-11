<?php

namespace PhpMvc\Framework\Data;

class Pluralize
{
    private bool $enabled;
    private string $locale;
    private array $pluralizeRegex/*  = [
        'en' => [
            [
                'match' => '/[^aeiou]y$/i',
                'replace' => '/y$/i',
                'replacement' => 'ies'
            ],
            [
                'match' => '/(ch|s|sh|x|z)$/i',
                'replace' => '/$/i',
                'replacement' => 'es'
            ],
            [
                'match' => '/(f|fe)$/i',
                'replace' => '/(f|fe)$/i',
                'replacement' => 'ves'
            ]
        ],
        'es' => [
            [
                'match' => '/[aeiou]$/i',
                'replace' => '/$/i',
                'replacement' => 's'
            ],
            [
                'match' => '/z$/i',
                'replace' => '/z$/i',
                'replacement' => 'es'
            ],
            [
                'match' => '/[bcdfghjklmnñpqrstvwxyz]$/i',
                'replace' => '/$/i',
                'replacement' => 'es'
            ]
        ]
    ] */;

    public function __construct()
    {
        $this->enabled = env('PLURALIZE_TABLE_NAMES', true);
        $this->locale = env('LOCALE', 'en');
        $json = file_get_contents(__DIR__.'/pluralize.json');
        $this->pluralizeRegex = json_decode($json, true);
    }

    public function pluralize(string $tableName): string
    {
        $returnValue = '';

        if (!empty($tableName)) {
            $returnValue = strtolower($tableName);

            if ($this->enabled) {
                $locale = $this->locale;
                $rules = $this->pluralizeRegex[$locale] ?? $this->pluralizeRegex['en'];
                $i = 0;

                do {
                    $matchFound = false;
                    [$match, $replace, $replacement] = $rules[$i];

                    if ($matchFound = preg_match($match, $tableName)) {
                        $returnValue = preg_replace($replace, $replacement, $tableName);
                    }

                    $i++;
                } while ($i < count($rules) && !$matchFound);
            }
        }

        return $returnValue;
    }
}
