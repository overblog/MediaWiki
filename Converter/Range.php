<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Base;

/**
 * Description of Range
 *
 * @author xavier
 */
class Range extends Base
{
    /**
     * Start of the range
     * @var int
     */
    public $start;

    /**
     * End of the range
     * @var int
     */
    public $end;

    /**
     * Instianciate a new Range
     * @param type $start
     * @param type $end
     */
    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }
}

