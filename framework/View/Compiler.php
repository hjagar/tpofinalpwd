<?php

namespace PhpMvc\Framework\View;

use InvalidArgumentException;

class Compiler
{
    private const ENVIRONMENT_CODE = "<?php use PhpMvc\\Framework\\View\\Environment;\n\$env = Environment::getInstance();?>";
    private array $patterns;
    private array $replacements;

    public function __construct()
    {
        $json = file_get_contents(__DIR__ . '/compiler-rules.json');
        $compilerRules = json_decode($json, true);
        $this->patterns = $compilerRules['patterns'];
        $this->replacements = $compilerRules['replacements'];
    }

    public function __get($name)
    {
        $returnValue = null;

        if (property_exists($this, $name)) {
            $returnValue = $this->$name;
        }

        return $returnValue;
    }

    public function compile(string $value): string
    {
        foreach ($this->patterns as $name => $regex) {
            $value = $this->$name($regex, $value);
        }

        $environmentCode = $this::ENVIRONMENT_CODE;
        $value = "{$environmentCode}\n{$value}";

        return $value;
    }

    private function echo($regex, $value, $replacementKey = 'echo')
    {
        $replacement = $this->replacements[$replacementKey];
        $value = preg_replace_callback($regex, function ($matches) use ($replacement) {
            $namedGroups = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);

            return str_replace('expr', $namedGroups['expr'], $replacement);
        }, $value);

        return $value;
    }

    private function raw($regex, $value)
    {
        return $this->echo($regex, $value, 'raw');
    }

    private function arroba($regex, $value)
    {
        $replacements = $this->replacements;
        $value = preg_replace_callback($regex, function ($matches) use ($replacements) {
            $namedGroups = array_filter($matches, fn($key) => !is_numeric($key), ARRAY_FILTER_USE_KEY);
            ['directive' => $directive, 'expr' => $replace] = $namedGroups + ['expr' => ""];

            $returnValue = "";

            if (isset($replacements[$directive])) {
                if ($replace) {
                    $returnValue = str_replace('expr', $replace, $replacements[$directive]);
                } else {
                    $returnValue = str_replace("@{$directive}", $replace, $replacements[$directive]);
                }
            }
            else {
                $returnValue = "@{$directive}";
            }
            
            return $returnValue;
        }, $value);

        return $value;
    }
}
