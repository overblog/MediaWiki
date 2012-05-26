<?php

namespace Overblog\Tests\MediaWiki\Converter;

use Overblog\MediaWiki\Converter\HtmlConverter;

/**
 * Description of HtmlConverter
 *
 * @author xavier
 */
class HtmlConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testLink()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd @Apple Store <a href="' .
                'http://instagr.am/p/JrzXrMqUln/">' .
                'http://instagr.am/p/JrzXrMqUln/</a>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store ' .
                'http:\/\/instagr.am\/p\/JrzXrMqUln\/",' .
                '"annotations":[{"type":"link\/external",' .
                '"range":{"start":26,"end":57},' .
                '"data":{"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}}]}}]}'
            );
    }

    public function testLinkWithForbiddenTag()
    {
        $mw = HtmlConverter::from(
                'Geek or <blink>Nerd</blink> @Apple Store <a href="' .
                'http://instagr.am/p/JrzXrMqUln/">' .
                'http://instagr.am/p/JrzXrMqUln/</a>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store ' .
                'http:\/\/instagr.am\/p\/JrzXrMqUln\/",' .
                '"annotations":[{"type":"link\/external",' .
                '"range":{"start":26,"end":57},' .
                '"data":{"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}}]}}]}'
            );
    }

    public function testOnlyText()
    {
        $mw = HtmlConverter::from('Geek or Nerd @Apple Store');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store"}}]}'
            );
    }

    public function testTextWithNewLine()
    {
        $mw = HtmlConverter::from('Geek or Nerd <br />@Apple Store');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd \\\n@Apple Store"}}]}'
            );
    }

    public function testMultipleLinkWithAccent()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd <a href="http://www.apple.com">@Applé</a> ' .
                'Store <a href="' .
                'http://instagr.am/p/JrzXrMqUln/">' .
                'http://instagr.am/p/JrzXrMqUln/</a>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Appl\u00e9 Store ' .
                'http:\/\/instagr.am\/p\/JrzXrMqUln\/",' .
                '"annotations":[{"type":"link\/external",' .
                '"range":{"start":13,"end":19},"data":{' .
                '"title":"http:\/\/www.apple.com"}},{"type":"link\/external",' .
                '"range":{"start":26,"end":57},"data":{' .
                '"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}}]}}]}'
            );
    }

    public function testWithBold()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd <b>@Apple</b> Store');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store",' .
                '"annotations":[{"type":"textStyle\/strong",' .
                '"range":{"start":13,"end":19}}]}}]}');
    }

    public function testMultipleBoldAnsStrong()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd <b>@Apple</b> <strong>Store</strong>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store",' .
                '"annotations":[{"type":"textStyle\/strong",' .
                '"range":{"start":13,"end":19}},{' .
                '"type":"textStyle\/strong","range":{"start":20,"end":25}' .
                '}]}}]}');
    }

    public function testWithLinkAndBold()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd @Apple Store <a href="' .
                'http://instagr.am/p/JrzXrMqUln/">' .
                '<b>http://instagr.am/p/JrzXrMqUln/</b></a>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store ' .
                'http:\/\/instagr.am\/p\/JrzXrMqUln\/",' .
                '"annotations":[{"type":"link\/external",' .
                '"range":{"start":26,"end":57},' .
                '"data":{"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}},' .
                '{"type":"textStyle\/strong","range":{"start":26,"end":57}' .
                '}]}}]}'
            );
    }

    public function testMultipleEmItalic()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd <i>@Apple</i> <em>Store</em>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store",' .
                '"annotations":[{"type":"textStyle\/emphasize",' .
                '"range":{"start":13,"end":19}},{' .
                '"type":"textStyle\/emphasize","range":{"start":20,"end":25}' .
                '}]}}]}');
    }

    public function testWithDel()
    {
        $mw = HtmlConverter::from(
                'Geek or Nerd <del>@Apple</del> Store');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store",' .
                '"annotations":[{"type":"textStyle\/delete",' .
                '"range":{"start":13,"end":19}}]}}]}');
    }

    public function testWithMultipleParagraph()
    {
        $mw = HtmlConverter::from(
                '<p>Geek or Nerd @Apple Store</p> ' .
                '<h2>A second apple paragraph</h2>' .
                '<pre>A third apple paragraph</pre>');

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"paragraph",' .
                '"content":{"text":"Geek or Nerd @Apple Store"' .
                '}},{"type":"heading",' .
                '"content":{"text":"A second apple paragraph"' .
                '},"attributes":{"level":"2"}},{"type":"pre",' .
                '"content":{"text":"A third apple paragraph"}}]}');
    }

    public function testWithOl()
    {
        $mw = HtmlConverter::from(
            '<ol><li>Ligne 1</li><li>Ligne 2</li><li>Ligne 3</li></ol>'
        );

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"list",' .
                '"children":[' .
                    '{"type":"listItem","children":[' .
                        '{"type":"paragraph","content":{"text":"Ligne 1"}}],' .
                            '"attributes":{"styles":["number"]}},' .
                    '{"type":"listItem","children":[' .
                        '{"type":"paragraph","content":{"text":"Ligne 2"}}],' .
                            '"attributes":{"styles":["number"]}},' .
                    '{"type":"listItem","children":[' .
                        '{"type":"paragraph","content":{"text":"Ligne 3"}}],' .
                            '"attributes":{"styles":["number"]}}]}]}');
    }

    public function testWithUlAndBlog()
    {
        $mw = HtmlConverter::from(
            '<ul><li>Ligne 1</li><li>Ligne <b>2</b></li><li>Ligne 3</li></ul>'
        );

        $this->assertInstanceOf(
                'Overblog\MediaWiki\Converter\Document', $mw);

        $this->assertEquals(
                $mw->asJson(),
                '{"type":"document","children":[{"type":"list",' .
                '"children":[' .
                    '{"type":"listItem","children":[' .
                        '{"type":"paragraph","content":{"text":"Ligne 1"}}],' .
                            '"attributes":{"styles":["bullet"]}},' .
                    '{"type":"listItem","children":[' .
                        '{"type":"paragraph","content":{"text":"Ligne 2",' .
                            '"annotations":[{"type":"textStyle\/strong",' .
                            '"range":{"start":6,"end":7}}]}}],' .
                            '"attributes":{"styles":["bullet"]}},' .
                    '{"type":"listItem","children":[' .
                        '{"type":"paragraph","content":{"text":"Ligne 3"}}],' .
                            '"attributes":{"styles":["bullet"]}}]}]}');
    }
}