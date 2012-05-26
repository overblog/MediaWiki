<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Base;

/**
 * Description of Data
 *
 * @author xavier
 */
class Data extends Base
{
    /**
     * Title
     * @var string
     */
    public $title;

    /**
     * Instanciate Data
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
    }
}

