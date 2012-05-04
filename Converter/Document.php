<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Children;

/**
 * Description of Document
 *
 * @author xavier
 */
class Document
{
    /**
     * Document type
     * @var string
     */
    public $type = 'document';

    /**
     * Document children
     * @var array
     */
    public $children = array();

    /**
     * Add children to Document
     * @param DocumentChildren $children
     */
    public function addChildren(Children $children)
    {
        $this->children[] = $children;
    }

    /**
     * Add childrens to Document
     * @param array $childrens
     */
    public function addChildrens(array $childrens)
    {
        foreach($childrens as $children)
        {
            $this->addChildren($children);
        }
    }

    /**
     * Return Document transformed in a JSON chain
     * @return type
     */
    public function asJson()
    {
        return json_encode($this);
    }
}

