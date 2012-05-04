<?php

namespace Overblog\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\Document;
use Overblog\MediaWiki\Converter\Children;

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
    private static $detectLeaf = array(
        'p' => Children::PARAGRAPH,
        'h1' => Children::HEADING,
        'h2' => Children::HEADING,
        'h3' => Children::HEADING,
        'h4' => Children::HEADING,
        'h5' => Children::HEADING,
        'h6' => Children::HEADING,
        'pre' => Children::PRE
    );

    /**
     * Tags replacement
     * @var array
     */
    private static $replaceTags = array(
        'b' => Children::STRONG,
        'strong' => Children::STRONG,
        'em' => Children::EMPHASIZE,
        'i' => Children::EMPHASIZE,
        'del' => Children::DEL
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
        $text = self::stripTags($text);

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
            $children = new Children(strip_tags($p['text']), $p['type'], $p['level']);

            // Detect links
            $links = self::detectLinks($p['text']);

            foreach($links as $l)
            {
                $children->createLink($l['link'], $l['start'], $l['end']);
            }

            // Detect simple tag
            $tags = self::detectTag($p['text']);

            foreach($tags as $t)
            {
                $children->createTag($t['tag'], $t['start'], $t['end']);
            }

            $childrens[] = $children;
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
                sprintf('#<(%s)>(.+?)</(?:%1$s)>#i', $leafSearch),
                $text,
                $match
            ))
        {
            foreach($match[2] as $k => $m)
            {
                $leaf = strtoupper(self::$detectLeaf[strtolower($match[1][$k])]);
                $level = null;

                if(preg_match('#^h([0-9])$#i', $match[1][$k], $l))
                {
                    $level = $l[1];
                }

                $texts[] = array(
                    'type' => constant('Overblog\MediaWiki\Converter\Children::' . $leaf),
                    'text' => $m,
                    'level' => $level
                );
            }
        }
        else
        {
            // Paragraph by default
            $texts[] = array(
                'type' => Children::PARAGRAPH,
                'text' => $text,
                'level' => null
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
        return preg_replace('#<br[ ]*/?>#i', '\n', $text);
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
        $strongs = array();
        $cleanText = strip_tags($text);

        $tagsSearch = implode('|', array_keys(self::$replaceTags));

        if(preg_match_all(
                sprintf('#<(%s)>(.+?)</(?:%1$s)>#i', $tagsSearch),
                $text,
                $match)
            )
        {
            foreach($match[0] as $k => $m)
            {
                //We get the position of the link in strip tags string
                $needle = strip_tags($match[2][$k]);
                $pos = mb_strpos($cleanText, $needle, 0, 'UTF-8');
                $tag = self::$replaceTags[strtolower($match[1][$k])];

                $strongs[] = array(
                    'tag' => constant('Overblog\MediaWiki\Converter\Children::' . $tag),
                    'start' => $pos,
                    'end' => $pos + mb_strlen($needle, 'UTF-8')
                );
            }
        }

        return $strongs;
    }
}