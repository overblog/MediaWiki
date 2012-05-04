<?php

namespace Overblog\MediaWiki\Converter;

/**
 * Description of Attribute
 *
 * @author xavier
 */
class Attribute
{
    /**
     * Level of heading
     * @var int
     */
    public $level;

    /**
     * Instianciate a new Attribute
     * @param int $level
     */
    public function __construct($level)
    {
        $this->level = $level;
    }
}

