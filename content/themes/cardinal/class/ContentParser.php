<?php

namespace Rdlv\WordPress\Theme;

use DOMElement;

class ContentParser
{
    /** @var Document */
    private $doc;
    
   
    /** @var Node */
    private $cursor = false;
    
    public function __construct($content)
    {
        $this->doc = new Document($content);
    }

    /**
     * @return Element
     */
    public function get($selector)
    {
        $nodes = $this->getAll($selector);
        return $nodes && $nodes->length ? $nodes[0] : null;
    }
    
    public function getAll($selector)
    {
        return $this->doc->find($selector);
    }
    
    private function initCursor()
    {
        if ($this->cursor === false) {
            $this->cursor = null;
            $nodes = $this->doc->find('body > :first-child');
            if ($nodes->length) {
                $this->cursor = $nodes->item(0);
            }
        }
    }

    /**
     * @param string $delimiter
     * @return string Resulting html
     */
    public function next($delimiter = 'hr')
    {
        $output = '';
        
        if ($this->cursor === null) {
            return $output;
        }
        
        $breaks = [];
        
        if ($delimiter !== null) {
            foreach ($this->doc->find('body ' . $delimiter) as $break) {
                $breaks[] = $break;
            }
        }
        
        $this->initCursor();
        
        while ($this->cursor !== null && !in_array($this->cursor, $breaks, true)) {
            $output .= $this->cursor->outerHtml();
            $this->cursor = $this->cursor->nextSibling;
        }
        
        // if not last node, advance to pass the break
        if ($this->cursor) {
            $this->cursor = $this->cursor->nextSibling;
        }
        
        return $output;
    }
    
    public function end()
    {
        return $this->next(null);
    }

    /**
     * @return Element
     */
    public function nextElement()
    {
        $this->initCursor();
        while ($this->cursor !== null && !$this->cursor instanceof DOMElement) {
            $this->cursor = $this->cursor->nextSibling;
        }
        /** @var Element $element */
        $element = $this->cursor;

        // if not last node, advance to pass next
        if ($this->cursor) {
            $this->cursor = $this->cursor->nextSibling;
        }
        return $element;
    }
}