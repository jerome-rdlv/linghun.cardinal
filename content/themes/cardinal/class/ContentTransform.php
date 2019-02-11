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
    public function addTransform($transform)
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
    
    public static function unwrapElement($query)
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
    
    public static function addClass($query, $class)
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
    
    public static function replaceTag($query, $tag, $class = null)
    {
        return function ($doc) use ($query, $tag, $class) {
            /** @var Document $doc */
            $nodes = $doc->find($query);

            /** @var Node $node */
            foreach ($nodes as $node) {
                $doc = $node->ownerDocument;

                /** @var Element $new */
                $new = $doc->createElement($tag);

                foreach ($node->attributes as $attribute) {
                    $new->setAttribute($attribute->name, $attribute->value);
                }
                while ($node->childNodes->length) {
                    $new->appendChild($node->childNodes->item(0));
                }
                if ($class !== null) {
                    $new->addClass($class);
                }
                $node->parentNode->insertBefore($new, $node);
                $node->parentNode->removeChild($node);
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


