<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Range;
use Overblog\MediaWiki\Converter\Data;

/**
 * Description of Annotation
 *
 * @author xavier
 */
class Annotation
{
    public $type;
    public $range;
    public $data;

    /**
     * Add a range
     * @param int $start
     * @param int $end
     */
    public function addRange($start, $end)
    {
        $this->range = new Range($start, $end);
    }

    /**
     * Add data
     * @param string $title
     */
    public function addData($title)
    {
        $this->data = new Data($title);
    }
}

