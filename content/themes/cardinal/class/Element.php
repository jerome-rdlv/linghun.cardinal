<?php

namespace Rdlv\WordPress\Theme;

class Element extends \DOMElement
{
    use NodeTrait;
    
    public function setAttribute($name, $value)
    {
        parent::setAttribute($name, $value);
        return $this;
    }
    
    public function addClass($classes)
    {
        $olds = explode(' ', $this->getAttribute('class'));
        $news = explode(' ', $classes);
        
        $news = array_merge($olds, $news);
        $news = array_unique($news);
        
        $this->setAttribute('class', implode(' ', $news));
    }
}