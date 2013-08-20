<?php

namespace Overblog\MediaWiki;

/**
 * File Document
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

use Overblog\MediaWiki\Node;

use Overblog\MediaWiki\LeafParagraph;
use Overblog\MediaWiki\LeafHeading;
use Overblog\MediaWiki\LeafPre;

/**
 * Class Document
 *
 * This class handles mediawiki documents.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

class Document
{

    /**
     * @var array $_nodes
     */

    private $_nodes = array();

    /**
     * Class constuctor
     *
     * @param mixed $data json StdClass, json string or HTML string
     */

    public function __construct($data)
    {
        if (is_object($data)
                and $data->type === Node::NODE_TYPE_DOCUMENT
        )
        {
            if (isset($data->children) and is_array($data->children))
            {
                foreach ($data->children as $c)
                {
                    $class = Node::getClassFromNode($c->type);

                    $this->_nodes[] = new $class($c);
                }
            }
        }
    }

    public function render()
    {
        $text = '';

        foreach ($this->_nodes as $n)
        {
            if ('Overblog\MediaWiki\LeafPre' === get_class($n))
            {
                $text .= $n->render();
            }
            else
            {
                $text .= nl2br($n->render());
            }
        }

        return $text;
    }
}