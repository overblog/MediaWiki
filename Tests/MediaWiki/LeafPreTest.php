<?php

namespace Overblog\Tests\MediaWiki;

use Overblog\MediaWiki\LeafPre;

/**
 * @group MediaWiki
 */

class LeafPreTestCase extends \PHPUnit_Framework_TestCase
{
	public function testRender1()
	{
        $leafJson =
            json_decode(
                '{' .
                    '"type":"pre",' .
                    '"content":{' .
                        '"text":"BLA BLO BLU"' .
                    '}' .
                '}'
            );

        $leaf = new LeafPre($leafJson);

		$this->assertEquals('<pre>BLA BLO BLU</pre>', $leaf->render());
	}

	public function testRender2()
	{

        $leafJson =
            json_decode(
                '{' .
                    '"type":"pre",' .
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

        $leaf = new LeafPre($leafJson);

		$this->assertEquals('<pre><b>BLA </b>BLO BLU</pre>', $leaf->render());
	}

	public function testRenderTorduDHadrien()
	{

        $leafJson =
            json_decode(
'{
    "type":"pre",
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

        $leaf = new LeafPre($leafJson);

		$this->assertEquals(
            '<pre>bla<b>bvlab<i>lablabl</b>abalbn</i></pre>',
            $leaf->render()
        );
	}

  	public function testRenderWithLink()
	{
        $leafJson =
            json_decode(
                '{"type":"pre","content":{"text":"Lions","annotations"' .
                ':[{"type":"link\/internal","range":{"start":0,"end":5},' .
                '"data":{"title":"http://www.google.fr"}}]}}'
            );

        $leaf = new LeafPre($leafJson);

		$this->assertEquals(
            '<pre><a href="http://www.google.fr">Lions</a></pre>',
            $leaf->render()
        );
    }

    public function testRenderWithNewline()
    {
        $leafJson =
            json_decode(
                '{' .
                    '"type":"pre",' .
                    '"content":{' .
                        '"text":"\\nBLA BLO BLU"' .
                    '}' .
                '}'
            );

        $leaf = new LeafPre($leafJson);

        $this->assertEquals("<pre>\nBLA BLO BLU</pre>", $leaf->render());
    }

}