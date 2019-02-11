<?php

namespace Rdlv\WordPress\Theme;

use DOMDocument;
use DOMXPath;
use Symfony\Component\CssSelector\CssSelectorConverter;

class Document extends DOMDocument
{
    const CHARSET_META = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
    
    /** @var DOMXPath */
    private $xpath;

    /** @var CssSelectorConverter */
    private $cssc;

    public function __construct($content)
    {
        parent::__construct('1.0', 'utf-8');
        
        $this->registerNodeClass('DOMElement', 'Rdlv\\WordPress\\Theme\\Element');
        $this->registerNodeClass('DOMNode', 'Rdlv\\WordPress\\Theme\\Node');
        $this->registerNodeClass('DOMText', 'Rdlv\\WordPress\\Theme\\Text');
        $this->registerNodeClass('DOMComment', 'Rdlv\\WordPress\\Theme\\Comment');

        if ($content && is_string($content)) {
            $this->loadHTML(self::CHARSET_META . $content);
        }

        $this->xpath = new DOMXPath($this);
        $this->cssc = new CssSelectorConverter();
    }

    /**
     * @param $selector
     * @return \DOMNodeList
     */
    public function find($selector)
    {
        return $this->xpath->query(
            $this->cssc->toXPath($selector)
        );
    }

    /**
     * @return string
     */
    public function html()
    {
        $output = '';
        $body = $this->find('body')->item(0);
        if ($body) {
            /** @var Node $node */
            foreach ($body->childNodes as $node) {
                $output .= $node->outerHtml();
            }
        }
        return $output;
    }
}