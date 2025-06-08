<?php

namespace PhpMvc\Framework\View;

class Compiler
{
    public function __construct(private string $compiledViewsPath)
    {
        
    }

    public function __get($name) {
        $returnValue = null;

        if(property_exists($this, $name)){
            $returnValue = $this->$name;
        }

        return $returnValue;
    }

    public function compile(string $viewFilename) : string
    {
        $regexExpressions = [
            [ '/\{\{ (\$[a-zA-Z_\d\-\>]+) \}\}/m', '<?php echo $1; ?>' ],
            [ '/@foreach\((.*)\)/m', '<?php foreach ($1): ?>' ],
            [ '/@endforeach/m', '<?php endforeach; ?>']
        ];

        $viewContent = file_get_contents($viewFilename);

        foreach($regexExpressions as $regex) {
            [$match, $replace] = $regex;
            $viewContent = preg_replace($match, $replace, $viewContent);
        }

        $hashedFilename = md5_file($viewFilename);
        $compiledFilename = "{$this->compiledViewsPath}/$hashedFilename.php";

        file_put_contents($compiledFilename, $viewContent);

        return $compiledFilename;
    }
}
