<?php

namespace TinyBlog\Tools;

use DOMDocument;
use DOMXPath;

class TeaserMaker
{
    public function makeTeaser($html)
    {
        $doc = new DOMDocument();
        $doc->loadHTML($html);

        $p = $doc->getElementsByTagName('p');
        if (!$p->length) {
            return '';
        };

        $html = $doc->saveHTML($p->item(0));
        if ($p->length > 1) {
            $html .= $doc->saveHtml($p->item(1));
        };

        return $html;
    }
}
