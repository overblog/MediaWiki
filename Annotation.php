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
 * This class handles Annotations.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

class Annotation
{
    const BOLD          = 'textStyle/bold';
    const ITALIC        = 'textStyle/italic';
    const EMPHASIZE     = 'textStyle/emphasize';
    const STRONG        = 'textStyle/strong';
    const DEL           = 'textStyle/delete';
    const BIG           = 'textStyle/big';
    const SMALL         = 'textStyle/small';
    const SUBSCRIPT     = 'textStyle/subScript';
    const SUPERSCRIPT   = 'textStyle/superScript';
    const LINK_INTERNAL = 'link/internal';
    const LINK_EXTERNAL = 'link/external';

    static protected $_tags =
        array(
            self::BOLD          => 'b',
            self::ITALIC        => 'i',
            self::EMPHASIZE     => 'em',
            self::STRONG        => 'strong',
            self::DEL           => 'del',
            self::BIG           => 'big',
            self::SMALL         => 'small',
            self::SUBSCRIPT     => 'sub',
            self::SUPERSCRIPT   => 'sup',
            self::LINK_INTERNAL => 'a',
            self::LINK_EXTERNAL => 'a',
        );

    static protected $_data2attribute =
        array(
            self::LINK_INTERNAL =>
                array(
                    'title' => 'href'
                ),
            self::LINK_EXTERNAL =>
                array(
                    'title' => 'href'
                )
        );

    static public function getTag(
        $type,
        $begin = true,
        $data = null)
    {
        $tag = '<' . (($begin === false) ? '/' : '') . self::$_tags[$type];

        if (($begin === true) and ! is_null($data))
        {
            foreach ($data as $k => $v)
            {
                $tag .=
                    ' ' .
                    self::$_data2attribute[$type][$k] .
                        '="' . addslashes($v) . '"';
            }
        }

        $tag .= '>';

        return $tag;
    }
}