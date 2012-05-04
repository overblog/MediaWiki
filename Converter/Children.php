<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Content;
use Overblog\MediaWiki\Annotation as AnnotationRender;
use Overblog\MediaWiki\Converter\Annotation;
use Overblog\MediaWiki\Converter\Attribute;

/**
 * Description of Children
 *
 * @author xavier
 */
class Children
{
    const STRONG = 'STRONG';
    const EMPHASIZE = 'EMPHASIZE';
    const DEL = 'DEL';

    const PARAGRAPH = 'paragraph';
    const HEADING = 'heading';
    const PRE = 'pre';

    /**
     * Children type
     * @var string
     */
    public $type;

    /**
     * Content
     * @var Content
     */
    public $content;

    /**
     * Attribute
     * @var type
     */
    public $attributes;

    /**
     * Create a new Children object
     * @param string $text
     * @param string $type
     * @param int $level
     */
    public function __construct($text, $type = self::PARAGRAPH, $level = null)
    {
        $this->type = $type;
        $this->content = new Content($text);

        if(!is_null($level))
        {
            $this->setAttributes($level);
        }
    }

    /**
     * Create a link
     * @param string $link
     * @param int $start
     * @param int $end
     */
    public function createLink($link, $start, $end)
    {
        $annotation = new Annotation();
        $annotation->addData($link);
        $annotation->addRange($start, $end);
        $annotation->type = AnnotationRender::LINK_EXTERNAL;

        $this->content->addAnnotation($annotation);
    }

    /**
     * Create Tag
     * @param int $start
     * @param int $end
     */
    public function createTag($tag, $start, $end)
    {
        $annotation = new Annotation();
        $annotation->addRange($start, $end);
        $annotation->type =
                constant('Overblog\MediaWiki\Annotation::' . $tag);

        $this->content->addAnnotation($annotation);
    }

    /**
     * Set attributes
     * @param int $level
     */
    public function setAttributes($level)
    {
        $this->attributes = new Attribute($level);
    }
}

