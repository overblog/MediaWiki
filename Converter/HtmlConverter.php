<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Document;
use Overblog\MediaWiki\Converter\Children;
use Overblog\MediaWiki\Converter\ChildrenList;

/**
 * Tool to convert HTML to MediaWiki
 *
 * @author xavier
 */
class HtmlConverter
{
    /**
     * Leaf replacement
     * @var array
     */
    private static $detectLeaf =
        array(
            'p'   => Children::L_PARAGRAPH,
            'h1'  => Children::L_HEADING,
            'h2'  => Children::L_HEADING,
            'h3'  => Children::L_HEADING,
            'h4'  => Children::L_HEADING,
            'h5'  => Children::L_HEADING,
            'h6'  => Children::L_HEADING,
            'pre' => Children::L_PRE,
            'ol'  => Children::L_LIST,
            'ul'  => Children::L_LIST
        );

    /**
     * Tags replacement
     * @var array
     */

    private static $replaceTags =
        array(
            'b'      => Children::STRONG,
            'strong' => Children::STRONG,
            'em'     => Children::EMPHASIZE,
            'i'      => Children::EMPHASIZE,
            'del'    => Children::DEL
        );

    /**
     * Create new Document from HTML
     * @param string $text
     */

    public static function from($text)
    {
        // Create objet

        $object = new Document();

        // Clean useless tags

        $text =
            htmlspecialchars_decode(
                self::stripTags($text),
                ENT_QUOTES | ENT_COMPAT | ENT_HTML401
            );

        // Place line in structure

        $object->addChildrens(self::createStructure($text));

        return $object;
    }

    /**
     * Remove useless Tags
     * @param type $text
     * @return type
     */

    private static function stripTags($text)
    {
        $list = array_merge(
            array_keys(self::$replaceTags),
            array_keys(self::$detectLeaf)
        );

        // Add missing tags
        $list[] = 'a';
        $list[] = 'br';
        $list[] = 'li';

        $list = implode('>,<', $list);
        $list = preg_replace('#(.+)#', '<$1>', $list);

        return strip_tags($text, $list);
    }

    /**
     * Create a new DocumentChildren structure
     * @param string $text
     */
    private static function createStructure($text)
    {
        // Replace <br /> by a new line

        $text = self::replaceNewLine($text);

        $childrens = array();

        //Detect paragraph

        $pgs = self::detectLeaf($text);

        foreach($pgs as $p)
        {
            if (! is_null($p['list']))
            {
                $liste = new Children(Children::L_LIST);

                foreach($p['text'] as $list)
                {
                    $listItem = new Children($list['type']);
                    $listItem->addChildrens($list['text']);

                    if(!empty($p['list']))
                    {
                        $listItem->addStyle($p['list']);
                    }

                    $liste->addChildren($listItem);
                }

                $childrens[] = $liste;
            }
            else
            {
                $childrens[] = self::buildChildren($p);
            }
        }

        return $childrens;
    }

    /**
     * Detect leaf
     * @param string $text
     * @return array
     */
    private static function detectLeaf($text)
    {
        $texts = array();

        $leafSearch = implode('|', array_keys(self::$detectLeaf));

        if(preg_match_all(
                sprintf('#<(%s)>(.+?)</(?:%1$s)>#si', $leafSearch),
                $text,
                $match
            ))
        {
            foreach($match[2] as $k => $m)
            {
                $leaf  =
                    strtoupper(
                        self::$detectLeaf[strtolower($match[1][$k])]
                    );
                $level = null;

                if (preg_match('#^h([0-9])$#i', $match[1][$k], $l))
                {
                    $level = $l[1];
                }

                switch($leaf)
                {
                    case 'LIST':
                        $texts[] = array(
                            'type' =>
                                constant(
                                    'Overblog\MediaWiki\Converter\Children::L_'
                                        . $leaf
                                ),
                            'text' => self::detectList($m),
                            'level' => null,
                            'list' =>
                                (
                                    ('ol' == $match[1][$k])
                                        ?
                                        'number'
                                        :
                                        'bullet'
                                )
                        );
                        break;

                    default :
                        $texts[] =
                            array(
                                'type'  =>
                                    constant(
                                        'Overblog\MediaWiki\Converter' .
                                            '\Children::L_' .
                                            $leaf
                                    ),
                                'text'  => $m,
                                'level' => $level,
                                'list'  => null
                            );
                }
            }
        }
        else
        {
            // Paragraph by default
            $texts[] = array(
                'type' => Children::L_PARAGRAPH,
                'text' => $text,
                'level' => null,
                'list' => null
            );
        }

        return $texts;
    }

    /**
     * Replace new line with a \n
     * @param string $text
     */
    private static function replaceNewLine($text)
    {
        return preg_replace('#<br[ ]*/?>#i', "\n", $text);
    }

    /**
     * Detect links
     * @param string $text
     * @return array
     */
    private static function detectLinks($text)
    {
        $links = array();
        $cleanText = strip_tags($text);

        if(preg_match_all('#<a[^>]*href=(?:\'|")(.+?)(?:\'|")[^>]*>(.+?)</a>#i',
                $text,
                $match
            ))
        {
            foreach($match[0] as $k => $m)
            {
                //We get the position of the link in strip tags string
                $needle = strip_tags($match[2][$k]);
                $pos = mb_strpos($cleanText, $needle, 0, 'UTF-8');

                $links[] = array(
                    'link' => $match[1][$k],
                    'start' => $pos,
                    'end' => $pos + mb_strlen($needle, 'UTF-8')
                );
            }
        }

        return $links;
    }

    /**
     * Detect tag
     * @param string $text
     * @return array
     */
    private static function detectTag($text)
    {
        $tags       = array();
        $cleanText  = strip_tags($text);
        $tagsSearch = implode('|', array_keys(self::$replaceTags));


        if(
            preg_match_all(
                sprintf('#<(%s)[^>]*>(.+?)</\1>#i', $tagsSearch),
                $text,
                $match)
            )
        {
            foreach($match[0] as $k => $m)
            {
                // We get the position of the link in strip tags string

                $needle = $match[2][$k];

                $subTags = self::detectTag($needle);

                if (count($subTags) > 0)
                {
                    $tags = array_merge($tags, $subTags);
                }

                $needle = strip_tags($match[2][$k]);
                $pos    = mb_strpos($cleanText, $needle, 0, 'UTF-8');
                $tag    = self::$replaceTags[strtolower($match[1][$k])];

                $tags[] =
                    array(
                        'tag'   =>
                            constant(
                                'Overblog\MediaWiki\Converter\Children::' .
                                    $tag
                            ),
                        'start' => $pos,
                        'end'   => $pos + mb_strlen($needle, 'UTF-8')
                    );
            }
        }

        return $tags;
    }

    /**
     * Detect list and build structure
     * @param string $text
     * @return array
     */
    private static function detectList($text)
    {
        $list = array();

        if(preg_match_all(
                '#<li>(.+?)</li>#i',
                $text,
                $match
            ))
        {
            foreach($match[0] as $k => $m)
            {
                $list[] = array(
                    'type' => Children::L_LISTITEM,
                    'text' => self::createStructure($match[1][$k]),
                    'level' => null,
                    'list' => null
                );
            }
        }

        return $list;
    }

    /**
     * Build children object
     * @param array $leaf
     * @return Children
     */
    private static function buildChildren(array $leaf)
    {
        $children = new Children($leaf['type']);
        $children->setText(strip_tags($leaf['text']));

        if(!is_null($leaf['level']))
        {
            $children->setLevel($leaf['level']);
        }

        // Detect links
        $links = self::detectLinks($leaf['text']);

        foreach($links as $l)
        {
            $children->createLink($l['link'], $l['start'], $l['end']);
        }

        // Detect simple tag

        $tags = self::detectTag($leaf['text']);

        foreach($tags as $t)
        {
            $children->createTag($t['tag'], $t['start'], $t['end']);
        }

        return $children;
    }
}