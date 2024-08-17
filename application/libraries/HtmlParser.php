<?php
class HtmlParser
{
    protected $htmlContent;
    protected $dom;

    public function __construct($args)
    {
        $this->htmlContent = $args[0];
        $this->dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $this->dom->loadHTML($this->htmlContent);
        libxml_clear_errors();
    }
    public function getContentByClass($class)
    {
        $xpath = new DOMXPath($this->dom);
        $elements = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");
        $results = [];
        foreach($elements as $element)
        {
            $innerHTML = '';
            foreach($element->childNodes as $child){
                $innerHTML .= $element->ownerDocument->saveHTML($child);
            };
            $results[] = $innerHTML;
        };
        return $results;
    }
}