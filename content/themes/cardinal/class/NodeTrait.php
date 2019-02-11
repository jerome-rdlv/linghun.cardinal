<?php

namespace Rdlv\WordPress\Theme;

use DOMNode;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Trait NodeTrait
 * @package Rdlv\WordPress\Theme
 * @property \DOMDocument $ownerDocument
 */
trait NodeTrait
{
    /** @var \DOMXPath */
    private $xpath = null;

    /** @var CssSelectorConverter */
    private $cssc = null;

    /**
     * @param $selector
     * @return NodeList
     */
    public function find($selector)
    {
        if ($this->xpath === null) {
            $this->xpath = new \DOMXPath($this->ownerDocument);
        }
        if ($this->cssc === null) {
            $this->cssc = new CssSelectorConverter(true);
        }
        return new NodeList(
            $this->xpath->query($this->cssc->toXPath($selector), $this)
        );
    }

    public function innerHtml()
    {
        $output = '';
        /** @var DOMNode $node */
        foreach ($this->childNodes as $node) {
            $output .= $this->ownerDocument->saveHTML($node);
        }
        return $output;
    }
    
    public function outerHtml()
    {
        return $this->ownerDocument->saveHTML($this);
    }
}