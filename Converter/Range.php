<?php

namespace Overblog\MediaWiki\Converter;

/**
 * Description of Range
 *
 * @author xavier
 */
class Range
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

