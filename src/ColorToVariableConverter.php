<?php

namespace radfuse;

/**
 * Converts color codes to variables in css code.
 * 
 * @author Barnabas Kiss <info@radfuse.com>
 */
class ColorToVariableConverter
{
    /** @var array */
    protected $_colorAsVariable = [];
    /** @var string */
    protected $_input;
    /** @var string */
    protected $_prefix;
    /** @var string */
    protected $_sigil;

    /**
     * Constructor
     * @param string $input
     */
    public function __construct($input, $preprocessorType, $prefix = 'color'){
        $this->_input = $input;
        $this->_prefix = $prefix;

        $this->setSigilByPreprocessorType($preprocessorType);
        $this->initializePrefix();
    }

    /**
     * Returns the conversion result
     * @return string
     */
    public function getResult(){
        $this->convertColorsToVariables();

        $variableDeclarations = $this->getVariableDeclarations();
        $replacedInput = $this->replaceColorsInInput();

        $result = $variableDeclarations;

        if(count($this->_colorAsVariable))
            $result .= "\n\n";

        $result .= $replacedInput;
            
        return  $result;
    }

    /**
     * Converts the colors from the input into variables
     */
    protected function convertColorsToVariables(){
        preg_match_all("/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b|rgb\((?:\s*\d+\s*,){2}\s*[\d]+\)|rgba\((\s*\d+\s*,){3}\s?[\d\.]+\)|hsl\(\s*\d+\s*(\s*\,\s*\d+\%){2}\)|hsla\(\s*\d+(\s*,\s*\d+\s*\%){2}\s*\,\s*[\d\.]+\)/", $this->_input, $colorsInInput);

        if(!count($colorsInInput[0]))
            return;

        $i = 1; // :(
        $colorsInInput = array_unique($colorsInInput[0]);

        foreach($colorsInInput as $color){
            $this->_colorAsVariable[$color] = $this->_prefix.$i;
            $i++;
        }
    }

    /**
     * Returns color variable declarations
     * @return string
     */
    protected function getVariableDeclarations(){
        $variables = [];
        foreach($this->_colorAsVariable as $color => $variable)
            $variables[] = $variable . ': ' . $color . ';';

        return implode("\n", $variables);
    }

    /**
     * Replaces colors in input to variables
     * @return string
     */
    protected function replaceColorsInInput(){
        return str_replace(array_keys($this->_colorAsVariable), array_values($this->_colorAsVariable), $this->_input);
    }

    /**
     * Initializes prefix
     */
    protected function initializePrefix(){
        if($this->_sigil && substr($this->_prefix, 0, 1) != $this->_sigil)
            $this->_prefix = $this->_sigil . $this->_prefix;
    }

    /**
     * Sets sigil by preprocessor type
     * @param string $preprocessorType
     * @throws Exception
     * @return string
     */
    protected function setSigilByPreprocessorType($preprocessorType){
        $preprocessorType = strtolower($preprocessorType);

        switch(strtolower($preprocessorType)){
            case 'less':
                $this->_sigil = '@';
                break;
            case 'scss':
            case 'sass':
                $this->_sigil = '$';
                break;
            default:
                throw new Exception('Invalid preprocessor type provided!');
                break;
        }
    }
}