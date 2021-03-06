<?php

namespace Overblog\MediaWiki;

/**
 * File Leaf
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

use Overblog\MediaWiki\Leaf;

/**
 * Class Leaf
 *
 * This class handles mediawiki leaf paragraph objects.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

class LeafSimple extends Leaf
{
    public function render()
    {
        return $this->_getAnnotatedText();
    }
}