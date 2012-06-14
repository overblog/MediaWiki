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
use Overblog\MediaWiki\BranchOrderedList;
use Overblog\MediaWiki\BranchUnorderedList;

/**
 * Class Branch
 *
 * This class handles content of text section objects.
 *
 * @package    Content
 * @subpackage Article content
 * @author     Yannick Le Guédart
 */

class BranchList extends Branch
{
    /**
     * Class constuctor
     *
     * Nested lists are handled quite curiously in MediaWiki in that they are
     * in no way nested, and that the type of list in the
     *
     * @param mixed $data json StdClass, json string or HTML string
     */

    public function __construct($data)
    {
        if (is_object($data))
        {
            if (isset($data->children) and is_array($data->children))
            {
                $previousType   = null; // Previous top-level list type
                $currentType    = null; // Current top-level list type
                $currentList    = null; // Current top-level list

                $previousLevel  = null; // previous element sub-level
                $currentSubList = null; // Current sub list

                foreach ($data->children as $c)
                {
                    // Détermination du type de la première liste
                    if(!isset($c->attributes))
                    {
                        $c->attributes = new \StdClass();
                        $c->attributes->styles = array();
                        $c->attributes->styles[] = 'bullet';
                    }

                    $currentType = $c->attributes->styles[0];

                    // Si la nouvelle liste est d'un type différent, on change

                    if ($previousType !== $currentType)
                    {
                        if (! is_null($currentList))
                        {
                            $class =
                                Node::getClassFromNode($currentList->type);

                            $this->_nodes[] = new $class($currentList);
                        }

                        $currentList   =
                            json_decode(
                                '{"type":"' .
                                    (
                                        ($currentType === 'number')
                                            ?
                                            'listOrdered'
                                            :
                                            'listUnordered'
                                    ) .
                                '","children":[]}'
                            );

                        $previousType = $currentType;
                    }

                    if (count($c->attributes->styles) > 1)
                    {
                        // ce n'est pas le même niveau que le précédent.
                        // On crée donc un objet de liste dans lequel on
                        // insère le listItem actuel en supprimant un niveau

                        if ($previousLevel !== $c->attributes->styles)
                        {
                            $currentSubList =
                                json_decode(
                                    '{"type":"listItem",' .
                                    '"children":[{"type":"list"}]}'
                                );

                            $previousLevel = $c->attributes->styles;

                            array_pop($c->attributes->styles);

                            $currentSubList->children[0]->children = array(clone($c));
                        }

                        // C'est le même niveau que le précédent, on ne fait
                        // que rajouter le listItem à la liste

                        else
                        {
                            array_pop($c->attributes->styles);

                            $currentSubList->children[0]->children[] = clone($c);
                        }

                    }
                    else // element de niveau top-level
                    {
                        if (! is_null($currentSubList))
                        {
                            $currentList->children[] = $currentSubList;
                        }

                        $currentList->children[] = clone($c);

                        $currentSubList = null;
                    }
                }

                if (! is_null($currentSubList))
                {
                    $currentList->children[] = $currentSubList;
                }

                if (! is_null($currentList))
                {
                    $class = Node::getClassFromNode($currentList->type);

                    $this->_nodes[] = new $class($currentList);
                }
            }

            if (isset($data->attributes))
            {
                $this->_attributes = $data->attributes;
            }
        }
    }
}