<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Annotation;

/**
 * Description of Content
 *
 * @author xavier
 */
class Content
{
    public $text;
    public $annotations = array();

    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Add Annotation to content
     * @param DocumentChildren $children
     */
    public function addAnnotation(Annotation $annotation)
    {
        $this->annotations[] = $annotation;
    }
}

