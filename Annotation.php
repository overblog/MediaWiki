<?php

/**
 * File Node
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

namespace Overblog\MediaWiki;

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

    /**
     * @var array List of valid wikidom tags and their HTML equivalents
     */

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

    /**
     * @var array liste des conversion Wikidom => html
     */

    static protected $_data2attribute =
        [
            self::LINK_INTERNAL =>
                [
                    'link'  => 'href="%s"',
                    'title' => 'href="%s"',
                    'popup' => 'class="popup"'
                ],
            self::LINK_EXTERNAL =>
                [
                    'link'  => 'href="%s"',
                    'title' => 'href="%s"',
                    'popup' => 'class="popup"'
                ]
        ];

    /**
     * Generate the html tag (begin or end) from the annotation
     *
     * @param string $type  tag type (@see self::$_tags)
     * @param bool   $begin whether it's the tag start or end
     * @param array  $data  array of special attributes
     *
     * @return string
     */

    static public function getTag(
        $type,
        $begin = true,
        $data  = null)
    {
        $tag = '<' . (($begin === false) ? '/' : '') . self::$_tags[$type];

        if (($begin === true) and ! is_null($data))
        {
            foreach ($data as $k => $v)
            {
                $tag .=
                    ' ' .
                    sprintf(
                        self::$_data2attribute[$type][$k],
                        addslashes($v)
                    );
            }
        }

        $tag .= '>';

        return $tag;
    }
}