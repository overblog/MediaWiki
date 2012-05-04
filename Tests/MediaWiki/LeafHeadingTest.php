<?php

namespace Overblog\Tests\MediaWiki;

use Overblog\MediaWiki\LeafHeading;

/**
 * @group MediaWiki
 */

class LeafHeadingTestCase extends \PHPUnit_Framework_TestCase
{
	public function testRender1()
	{
        $leafJson =
            json_decode(
                '{' .
                    '"type":"heading",' .
                    '"attributes":{"level":1},' .
                    '"content":{' .
                        '"text":"BLA BLO BLU"' .
                    '}' .
                '}'
            );

        $leaf = new LeafHeading($leafJson);

		$this->assertEquals('<h2>BLA BLO BLU</h2>', $leaf->render());
	}

	public function testRender2()
	{

        $leafJson =
            json_decode(
                '{' .
                    '"type":"heading",' .
                    '"attributes":{"level":2},' .
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

        $leaf = new LeafHeading($leafJson);

		$this->assertEquals('<h3><b>BLA </b>BLO BLU</h3>', $leaf->render());
	}


  	public function testRenderWithLink()
	{
        $leafJson =
            json_decode(
                '{"type":"heading","attributes":{"level":3},' .
                '"content":{"text":"Lions","annotations"' .
                ':[{"type":"link\/internal","range":{"start":0,"end":5},' .
                '"data":{"title":"http://www.google.fr"}}]}}'
            );

        $leaf = new LeafHeading($leafJson);

		$this->assertEquals(
            '<h4><a href="http://www.google.fr">Lions</a></h4>',
            $leaf->render()
        );
    }
}