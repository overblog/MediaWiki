<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Content;
use Overblog\MediaWiki\Annotation as AnnotationRender;
use Overblog\MediaWiki\Converter\Annotation;
use Overblog\MediaWiki\Converter\Attribute;
use Overblog\MediaWiki\Converter\Base;

/**
 * Description of Children
 *
 * @author xavier
 */
class Children extends Base
{
    const STRONG = 'STRONG';
    const EMPHASIZE = 'EMPHASIZE';
    const DEL = 'DEL';

    const L_PARAGRAPH = 'paragraph';
    const L_HEADING = 'heading';
    const L_PRE = 'pre';
    const L_LIST = 'list';
    const L_LISTITEM = 'listItem';

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
     * Children of childre
     * @var array
     */
    public $children = array();

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
    public function __construct($type = self::PARAGRAPH)
    {
        $this->type = $type;
    }

    /**
     * Set text
     * @param string $text
     */
    public function setText($text)
    {
        $this->content = new Content($text);
    }

    /**
     * Add children to Document
     * @param mixed $children
     */
    public function addChildren(Children $children)
    {
        $this->children[] = $children;
    }

    /**
     * Add childrens to Document
     * @param array $childrens
     */
    public function addChildrens(array $childrens)
    {
        foreach($childrens as $children)
        {
            $this->addChildren($children);
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
     * Init attribute property
     */
    protected function initAttribute()
    {
        if(is_null($this->attributes))
        {
            $this->attributes = new Attribute();
        }
    }

    /**
     * Set attributes
     * @param int $level
     */
    public function setLevel($level)
    {
        $this->initAttribute();

        $this->attributes->level = $level;
    }

    /**
     * Set attributes
     * @param int $level
     */
    public function addStyle($style)
    {
        $this->initAttribute();

        $this->attributes->styles[] = $style;
    }
}

