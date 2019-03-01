<?php

namespace Rdlv\WordPress\Theme;

class ContentTransform
{
    /** @var callable[] */
    private $transforms = [];
    
    /**
     * @param $transforms callable[]
     */
    public function __construct($transforms)
    {
        $this->transforms = $transforms;
    }

    /**
     * @param $transform callable|array
     */
    public function add_transform($transform)
    {
        $this->transforms[] = $transform;
    }

    /**
     * @param string $content
     * @return string
     */
    public function run($content)
    {
        $doc = new Document($content);
        
        foreach ($this->transforms as $transform) {
            if (is_callable($transform)) {
                call_user_func($transform, $doc);
            }
        }
        
        return $doc->html();
    }
    
    public static function unwrap_element($query)
    {
        return function ($doc) use ($query) {
            /** @var Document $doc */
            $nodes = $doc->find($query);
            
            $parents = array();

            /** @var Node $node */
            foreach ($nodes as $node) {
                $parent = $node->parentNode;
                
                // do not unwrap if there are other elements in the parent
                if ($parent->childNodes->length === 1) {
                    // do not unwrap if root element
                    if ($parent->parentNode) {
                        $parent->parentNode->insertBefore($node, $parent);
                        
                        if (!array_search($parent, $parents, true)) {
                            $parents[] = $parent;
                        }
                    }
                }
            }
            
            foreach ($parents as $parent) {
                /* @var $parent Element */
                $parent->parentNode->removeChild($parent);
            }
        };
    }
    
    public static function add_class($query, $class)
    {
        return function ($doc) use ($query, $class) {
            /** @var Document $doc */
            $nodes = $doc->find($query);
            
            /** @var Node $node */
            foreach ($nodes as $node) {
                if ($node instanceof Element) {
                    $node->addClass($class);
                }
            }
        };
    }
    
    public static function replace_tag($query, $tag, $class = null)
    {
        return function ($doc) use ($query, $tag, $class) {
            /** @var Document $doc */
            $elements = $doc->find($query);

            /** @var Element $element */
            foreach ($elements as $element) {
                $element->addClass($class);
                ContentTransform::replace_element_tag($element, $tag);
            }
        };
    }
    
    private static function replace_element_tag(Element $element, $tag)
    {
        $doc = $element->ownerDocument;
        
        /** @var Element $new */
        $new = $doc->createElement($tag);

        // copy attributes
        foreach ($element->attributes as $attribute) {
            $new->setAttribute($attribute->name, $attribute->value);
        }
        
        // copy children
        while ($element->childNodes->length) {
            $new->appendChild($element->childNodes->item(0));
        }
        
        // replace element
        $element->parentNode->replaceChild($new, $element);
    }
    
    public static function down_headings()
    {
        return function ($doc) {
            /** @var Document $doc */
            $elements = $doc->find('h1, h2, h3, h4, h5, h6');
            
            /** @var Element $element */
            foreach ($elements as $element) {
                /** @noinspection PhpUndefinedFieldInspection */
                preg_match('/h([0-9])/', $element->tagName, $m);
                ContentTransform::replace_element_tag($element, 'h'. min(($m[1] + 1), 6));
            }
        };
    }
    
    public static function merge($query, $tag = null, $class = null)
    {
        return function ($doc) use ($query, $tag, $class) {
            /** @var Document $doc */
            $nodes = $doc->find($query);
            
            if ($nodes->length) {

                $candidates = [];
                foreach ($nodes as $node) {
                    $candidates[] = $node;
                }
                
                $doc = $candidates[0]->ownerDocument;
                
                while (count($candidates) > 0) {
                    $node = $candidates[0];
                    
                    // create new parent element
                    $new = $doc->createElement($tag ? $tag : $node->tagName);
                    foreach ($node->attributes as $attribute) {
                        $new->setAttribute($attribute->name, $attribute->value);
                    }
                    if ($class) {
                        $new->setAttribute('class', $class);
                    }
                    
                    // append new element to document
                    $node->parentNode->insertBefore($new, $node);
                    
                    do {
                        $index = array_search($node, $candidates, true);
                        $next = $node->nextSibling;
                        
                        if ($node instanceof Text) {
                            $new->appendChild($node);
                        }
                        else {
                            if ($index !== false) {
                                // one of the candidates
                                while ($node->childNodes->length) {
                                    $new->appendChild($node->childNodes->item(0));
                                }
                                // remove from document
                                $node->parentNode->removeChild($node);
                            }
                            else {
                                // eligible node between
                                $new->appendChild($node);
                            }
                        }
                        
                        // drop node from candidates
                        if ($index !== false) {
                            array_shift($candidates);
                        }
                        
                        $node = $next;
                    } while (
                        $node && (
                            ($node instanceof Text && preg_match('/^ *$/', $node->textContent))
                            ||
                            in_array($node, $candidates, true)
                            ||
                            ($node instanceof Element && $node->tagName === 'br')
                        )
                    );
                }
            }
        };
    }
}


