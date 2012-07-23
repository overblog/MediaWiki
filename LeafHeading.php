<?php

namespace Overblog\MediaWiki;

/**
 * File LeafHeading
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

use Overblog\MediaWiki\Leaf;

/**
 * Class LeafHeading
 *
 * This class handles mediawiki leaf heading objects.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

class LeafHeading extends Leaf
{
    public function render()
    {
        $level = $this->_attributes->level + 2;

        return
            '<h' . $level . '>' .
                $this->_getAnnotatedText() .
            '</h' . $level . '>';
    }
}