<?php

namespace Overblog\MediaWiki;

/**
 * File LeafPre
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

use Overblog\MediaWiki\Leaf;

/**
 * Class LeafPre
 *
 * This class handles mediawiki leaf paragraph objects.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

class LeafPre extends Leaf
{
    public function render()
    {
        return '<pre>' . $this->_getAnnotatedText() . '</pre>';
    }
}