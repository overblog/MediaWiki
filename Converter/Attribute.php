<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Base;

/**
 * Description of Attribute
 *
 * @author xavier
 */
class Attribute extends Base
{
    /**
     * Level of heading
     * @var int
     */
    public $level;

    /**
     * List style
     * @var array
     */
    public $styles = array();
}

