<?php

namespace Overblog\MediaWiki;

/**
 * File Leaf
 *
 * @package    MediaWiki
 * @subpackage Document
 * @version    1.0
 * @author     Yannick Le Guédart
 */

/**
 * Class Leaf
 *
 * This class handles mediawiki leaf objects.
 *
 * @package    MediaWiki
 * @subpackage Document
 * @author     Yannick Le Guédart
 */

abstract class Leaf
{
    /**
     * text of the leaf
     *
     * @var string $_text raw text
     */

    protected $_text = null;

    /**
     * Annotation
     *
     * @var array $_annotations
     */

    protected $_annotations = array();

    /**
     * attributes
     *
     * @var array $_annotations
     */

    protected $_attributes;

    /* SpecialChars converter */
    protected $_specialchars = array(
      'LT' => array(
        'original' => '<',
        'replacement' => '⫷',
        'entities'    => '&lt;'
      ),
      'GT' => array(
        'original' => '>',
        'replacement' => '⫸',
        'entities'    => '&gt;'
      ),
      'QUOTE' => array(
        'original' => '\'',
        'replacement' => '⧘',
        'entities'    => '&#039;'
      ),
      'DOUBLE_QUOTE' => array(
        'original' => '"',
        'replacement' => '⧚',
        'entities'    => '&quot;'
      )
    );

    /**
     * Class constructor
     *
     * @param Object $leaf json_object
     */

    public function __construct($leaf)
    {
        $this->_text = $leaf->content->text;

        if (isset($leaf->content->annotations))
        {
            $this->_annotations = $leaf->content->annotations;
        }

        if (isset($leaf->attributes))
        {
            $this->_attributes = $leaf->attributes;
        }
    }

    /**
     * Encode special chars with strange chars
     *
     * @param $text
     * @return string
     */
    protected function _encodeText($text)
    {
        foreach($this->_specialchars as $special)
        {
          $text = preg_replace(
            '/'.$special['original'].'/',
            $special['replacement'],
            $text
          );

        }

        return $text;
    }

    /**
     * HTML entities chars that converted by $this->_encodeText()
     *
     * @param $encodedtext
     * @return string
     */
    protected function _entitiesText($encodedtext)
    {
        foreach($this->_specialchars as $special)
        {
          $encodedtext = preg_replace(
            '/'.$special['replacement'].'/',
            $special['entities'],
            $encodedtext
          );

        }

        return $encodedtext;
    }

    /**
     * Returns the text modified by annotations
     *
     * @return string
     */
    protected function _getAnnotatedText()
    {
        $list = array();

        // creation order for annotation
        foreach ($this->_annotations as $i => $a)
        {
            // end - Must be set first because we close before open tags

            if (! isset($list[$a->range->end]))
            {
                $list[$a->range->end] = array();
            }

            $list[$a->range->end][] =
                array(
                    'type'  => $a->type,
                    'begin' => false,
                    'data'  => null
                );

            // begin

            if (! isset($list[$a->range->start]))
            {
                $list[$a->range->start] = array();
            }

            $list[$a->range->start][] =
                array(
                    'type'  => $a->type,
                    'begin' => true,
                    'data'  => (isset($a->data) ? $a->data : null)
                );
        }

        ksort($list);

        // génération du vrai texte
        if(count($list) > 0)
        {
            $length = 0;

            $text = $this->_encodeText($this->_text);

            foreach ($list as $char => $annotations)
            {
                $replace = null;
                $pos = $char + $length;

                foreach($annotations as $ann)
                {
                    $replace.= Annotation::getTag(
                               $ann['type'],
                               $ann['begin'],
                               $ann['data']
                           );
                }

                $len = mb_strlen($replace);

                $text = mb_substr($text, 0, $pos) . $replace .
                        mb_substr($text, $pos);

                $length+= $len;
            }

            $text = $this->_entitiesText($text);
        }
        else
        {
            $text = htmlspecialchars($this->_text, ENT_QUOTES, 'UTF-8');
        }

        return $text;
    }
}