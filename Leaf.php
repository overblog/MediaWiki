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

            // end

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

        }

        // génération du vrai texte
        if(count($list) > 0)
        {
            $text = '';

            $textTransformed = $this->_encodeText($this->_text);

            $mb_text = preg_split(
                '/(?<!^)(?!$)/u', $textTransformed
            );
            $strlen  = count($mb_text);

            for ($i = 0 ; $i < $strlen ; $i++)
            {
                if (isset($list[$i]))
                {
                    foreach ($list[$i] as $ann)
                    {
                        $text .=
                            Annotation::getTag(
                                $ann['type'],
                                $ann['begin'],
                                $ann['data']
                            );
                    }
                }
                $text .= $mb_text[$i];
            }

            // fin de chaine

            if (isset($list[$strlen]))
            {
                foreach ($list[$strlen] as $ann)
                {
                    $text .=
                        Annotation::getTag(
                            $ann['type'],
                            $ann['begin'],
                            $ann['data']
                        );
                }
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