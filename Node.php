<?php

namespace Overblog\MediaWiki;

/**
 * File Node
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

/**
 * Class Node
 *
 * This class handles node type and constants.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

class Node
{
    /**
     * Node type constants
     */

    const NODE_TYPE_DOCUMENT = 'document';
    const NODE_TYPE_BRANCH   = 'branch';
    const NODE_TYPE_LEAF     = 'leaf';

    /**
     * Available node constants
     */

    const NODE_PARAGRAPH = 'paragraph';
    const NODE_HEADING   = 'heading';
    const NODE_PRE       = 'pre';
    const NODE_LIST      = 'list';
    const NODE_LIST_OL   = 'listOrdered';   // Not in wikimedia
    const NODE_LIST_UL   = 'listUnordered'; // Not in wikimedia
    const NODE_LIST_ITEM = 'listItem';

    /**
     * @var $node2Type array Association node => type
     */

    static protected $_node2Type =
        array(
            self::NODE_PARAGRAPH => self::NODE_TYPE_LEAF,
            self::NODE_HEADING   => self::NODE_TYPE_LEAF,
            self::NODE_PRE       => self::NODE_TYPE_LEAF,
            self::NODE_LIST      => self::NODE_TYPE_BRANCH,
            self::NODE_LIST_OL   => self::NODE_TYPE_BRANCH,
            self::NODE_LIST_UL   => self::NODE_TYPE_BRANCH,
            self::NODE_LIST_ITEM => self::NODE_TYPE_BRANCH,
        );

    /**
     * @var $_node2Class array Association type => class
     */

    static protected $_node2Class =
        array(
            self::NODE_PARAGRAPH =>
                'Overblog\MediaWiki\LeafParagraph',
            self::NODE_HEADING   =>
                'Overblog\MediaWiki\LeafHeading',
            self::NODE_PRE       =>
                'Overblog\MediaWiki\LeafPre',
            self::NODE_LIST      =>
                'Overblog\MediaWiki\BranchList',
            self::NODE_LIST_OL   =>
                'Overblog\MediaWiki\BranchListOrdered',
            self::NODE_LIST_UL   =>
                'Overblog\MediaWiki\BranchListUnordered',
            self::NODE_LIST_ITEM =>
                'Overblog\MediaWiki\BranchListItem',
        );

    /**
     * Get class from node
     *
     * @param string $nodeName type of the node
     *
     * @return string
     */

    static function getClassFromNode($nodeName)
    {
        return self::$_node2Class[$nodeName];
    }
}