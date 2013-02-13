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

use Overblog\MediaWiki\LeafSimple;

/**
 * Class Branch
 *
 * This class handles content of text section objects.
 *
 * @package    Content
 * @subpackage Article content
 * @author     Yannick Le Guédart
 */

class Branch
{
    /**
     * @var array $_nodes
     */

    protected $_nodes = array();

    /**
     * @var string Content of the list item
     */

    protected $_content = null;

    /**
     * attributes
     *
     * @var array $_annotations
     */

    protected $_attributes;

    /**
     * Class constuctor
     *
     * @param mixed $data json StdClass, json string or HTML string
     */

    public function __construct($data)
    {
        if (is_object($data))
        {
            if (isset($data->children) and is_array($data->children))
            {
                foreach ($data->children as $c)
                {
                    $class = Node::getClassFromNode($c->type);

                    $this->_nodes[] = new $class($c);
                }
            }

            if (isset($data->content))
            {
                $this->_content = new LeafSimple($data);
            }

            if (isset($data->attributes))
            {
                $this->_attributes = $data->attributes;
            }
        }
    }

    public function render()
    {
        $text = '';

        if (! is_null($this->_content))
        {
            $text .= $this->_content->render();
        }

        foreach ($this->_nodes as $n)
        {
            $text .= $n->render();
        }

        return $text;
    }
}