<?php

namespace Overblog\MediaWiki;

/**
 * File Branch
 *
 * @package    Content
 * @subpackage Article content
 * @version    1.0
 * @author     Yannick Le Guédart
 */

use Overblog\MediaWiki\Branch;

/**
 * Class Branch
 *
 * This class handles content of text section objects.
 *
 * @package    Content
 * @subpackage Article content
 * @author     Yannick Le Guédart
 */

class BranchListItem extends Branch
{
    public function getStyle()
    {
        if (
            isset($this->_attributes->styles)
            and
            is_array($this->_attributes->styles)
            and
            ! empty($this->_attributes->styles)
        )
        {
            return $this->_attributes->styles[0];
        }
        else
        {
            return null;
        }
    }

    public function render()
    {
        return '<li>' . parent::render() . '</li>';
    }
}