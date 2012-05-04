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

class BranchListOrdered extends Branch
{
    public function render()
    {
        return '<ol>' . parent::render() . '</ol>';
    }
}