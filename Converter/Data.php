<?php

namespace Overblog\MediaWiki\Converter;

/**
 * Description of Data
 *
 * @author xavier
 */
class Data
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

