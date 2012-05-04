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
                '"data":{"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}}]},' .
                '"attributes":null}]}'
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
                '"data":{"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}}]},' .
                '"attributes":null}]}'
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
                '"content":{"text":"Geek or Nerd @Apple Store",' .
                '"annotations":[]},"attributes":null}]}'
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
                '"content":{"text":"Geek or Nerd \\\n@Apple Store",' .
                '"annotations":[]},"attributes":null}]}'
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
                '"title":"http:\/\/instagr.am\/p\/JrzXrMqUln\/"}}]},' .
                '"attributes":null}]}'
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
                '"range":{"start":13,"end":19},"data":null}]},' .
                '"attributes":null}]}');
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
                '"range":{"start":13,"end":19},"data":null},{' .
                '"type":"textStyle\/strong","range":{"start":20,"end":25},' .
                '"data":null}]},"attributes":null}]}');
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
                '{"type":"textStyle\/strong","range":{"start":26,"end":57},' .
                '"data":null}]},"attributes":null}]}'
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
                '"range":{"start":13,"end":19},"data":null},{' .
                '"type":"textStyle\/emphasize","range":{"start":20,"end":25},' .
                '"data":null}]},"attributes":null}]}');
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
                '"range":{"start":13,"end":19},"data":null}]},' .
                '"attributes":null}]}');
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
                '"content":{"text":"Geek or Nerd @Apple Store",' .
                '"annotations":[]},"attributes":null},{"type":"heading",' .
                '"content":{"text":"A second apple paragraph",' .
                '"annotations":[]},"attributes":{"level":"2"}},{"type":"pre",' .
                '"content":{"text":"A third apple paragraph",' .
                '"annotations":[]},"attributes":null}]}');
    }
}