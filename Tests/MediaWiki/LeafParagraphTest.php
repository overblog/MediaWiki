<?php

namespace Overblog\Tests\MediaWiki;

use Overblog\MediaWiki\LeafParagraph;

/**
 * @group MediaWiki
 */

class LeafParagraphTestCase extends \PHPUnit_Framework_TestCase
{
    public function testRender1()
    {
        $leafJson =
            json_decode(
                '{' .
                    '"type":"paragraph",' .
                    '"content":{' .
                        '"text":"BLA BLO BLU"' .
                    '}' .
                '}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals('<p>BLA BLO BLU</p>', $leaf->render());
    }

    public function testRender2()
    {

        $leafJson =
            json_decode(
                '{' .
                    '"type":"paragraph",' .
                    '"content":{' .
                        '"text":"BLA BLO BLU",' .
                        '"annotations":' .
                            '[' .
                                '{' .
                                    '"type":"textStyle/bold",' .
                                    '"range":{"start":0,"end":4}' .
                                '}' .
                            ']' .
                    '}' .
                '}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals('<p><b>BLA </b>BLO BLU</p>', $leaf->render());
    }

    public function testRenderTorduDHadrien()
    {

        $leafJson =
            json_decode(
'{
    "type":"paragraph",
    "content":
    {
        "text":"blabvlablablablabalbn",
        "annotations":
        [
            {
                "type":"textStyle/bold",
                "range":
                {
                    "start":3,
                    "end":15
                }
            },
            {
                "type":"textStyle/italic",
                "range":
                {
                    "start":8,
                    "end":21
                }
            }
        ]
    }
}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals(
            '<p>bla<b>bvlab<i>lablabl</b>abalbn</i></p>',
            $leaf->render()
        );
    }

      public function testRenderWithLink()
    {
        $leafJson =
            json_decode(
                '{"type":"paragraph","content":{"text":"Lions","annotations"' .
                ':[{"type":"link\/internal","range":{"start":0,"end":5},' .
                '"data":{"title":"http://www.google.fr"}}]}}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals(
            '<p><a href="http://www.google.fr">Lions</a></p>',
            $leaf->render()
        );
    }

    /**
     *@group glou
     */

    public function testRenderWithUTF8()
    {
        $leafJson =
            json_decode(
                '{"type":"paragraph","content":{"text":"Voilà voilà",' .
                '"annotations"' .
                ':[{"type":"textStyle/bold","range":{"start":3,"end":6}}]}}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals(
            '<p>Voi<b>là </b>voilà</p>',
            $leaf->render()
        );
    }

    public function testRenderWithUTF8WithHtml()
    {
        $leafJson =
            json_decode(
                '{"type":"paragraph","content":{"text":"Voilà <b>voilà</b>",' .
                '"annotations"' .
                ':[{"type":"textStyle/bold","range":{"start":3,"end":6}}]}}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals(
            '<p>Voi<b>là </b>&lt;b&gt;voilà&lt;/b&gt;</p>',
            $leaf->render()
        );
    }

    public function testRenderWithDel()
    {
        $leafJson =
            json_decode(
                '{"type":"paragraph","content":{"text":"Lions","annotations"' .
                ':[{"type":"textStyle/delete","range":{"start":0,"end":5}}]}}'
            );

        $leaf = new LeafParagraph($leafJson);

        $this->assertEquals(
            '<p><del>Lions</del></p>',
            $leaf->render()
        );
    }
}