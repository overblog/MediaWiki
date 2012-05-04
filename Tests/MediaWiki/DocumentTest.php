<?php

namespace Overblog\Tests\MediaWiki;

use Overblog\MediaWiki\Document;

/**
 * @group MediaWiki
 */

class DocumentTestCase extends \PHPUnit_Framework_TestCase
{
	public function testRender1()
	{
        $json =
            json_decode(
                '{"type":"document","children":[{"type":"heading",' .
                '"attributes":{"level":1},"content":{"text":"Ceci est ' .
                'un titre","annotations":[{"type":"textStyle/italic",' .
                '"range":{"start":5,"end":8}},{"type":"textStyle/bold",' .
                '"range":{"start":12,"end":17}}]}},{"type":"heading",' .
                '"attributes":{"level":2},"content":{"text":"Ceci est un ' .
                'sous titre"}},{"type":"heading","attributes":{"level":3},' .
                '"content":{"text":"Ceci est saoul titre"}},{"type":' .
                '"paragraph","content":{"text":"Et voilà enfin un ' .
                'paragraphe. Ouf. Il était temps.","annotations":' .
                '[{"type":"textStyle\/bold","range":{"start":5,"end":10}}]}}' .
                ',{"type":"paragraph","content":{"text":"Eh ouais !"}}]}'
            );

        $doc = new Document($json);

        $this->assertEquals(
            '<h2>Ceci <i>est</i> un <b>titre</b></h2>' .
                '<h3>Ceci est un sous titre</h3>' .
                '<h4>Ceci est saoul titre</h4>' .
                '<p>Et vo<b>ilà e</b>nfin un paragraphe. Ouf. Il était temps.</p>' .
                '<p>Eh ouais !</p>'
            ,
            $doc->render()
        );
	}

	public function testRender2()
	{
        $json =
            json_decode('
{
    "type":"document",
    "children":[
        {
            "type":"heading",
            "attributes":{
                "level":1
            },
            "content":{
                "text":"Ceci est un titre",
                "annotations":[
                {
                    "type":"textStyle\/italic",
                    "range":{
                        "start":5,
                        "end":8
                    }
                },
                {
                    "type":"textStyle\/bold",
                    "range":{
                        "start":12,
                        "end":17
                    }
                }
                ]
            }
        },
        {
            "type":"heading",
            "attributes":{
                "level":2
            },
            "content":{
                "text":"Ceci est un sous titre"
            }
        },
        {
            "type":"heading",
            "attributes":{
                "level":3
            },
            "content":{
                "text":"Ceci est saoul titre"
            }
        },
        {
            "type":"paragraph",
            "content":{
                "text":"Et voilà enfin un paragraphe. Ouf. Il était temps.",
                "annotations":[
                {
                    "type":"textStyle\/bold",
                    "range":{
                        "start":44,
                        "end":49
                    }
                }
                ]
            }
        },
        {
            "type":"paragraph",
            "content":{
                "text":"Listons les points positifs :"
            }
        },
        {
            "type":"list",
            "children":[
                {
                "type":"listItem",
                "attributes":{
                    "styles":[
                        "number"
                    ]
                },
                "children":[
                    {
                        "type":"paragraph",
                        "content":{
                            "text":"C\'est cool"
                        }
                    }
                ]
                },
                {
                "type":"listItem",
                "attributes":{
                    "styles":[
                        "number"
                    ]
                },
                "children":[
                    {
                        "type":"paragraph",
                        "attributes":{

                        },
                        "content":{
                            "text":"C\'est beau"
                        }
                    }
                ]
                },
                {
                "type":"listItem",
                "attributes":{
                    "styles":[
                        "number",
                        "number"
                    ]
                },
                "children":[
                    {
                        "type":"paragraph",
                        "attributes":{

                        },
                        "content":{
                            "text":"Même très beau"
                        }
                    }
                ]
                },
                {
                "type":"listItem",
                "attributes":{
                    "styles":[
                        "number"
                    ]
                },
                "children":[
                    {
                        "type":"paragraph",
                        "attributes":{

                        },
                        "content":{
                            "text":"C\'est génial"
                        }
                    }
                ]
                }
            ]
        },
        {
            "type":"paragraph",
            "attributes":{

            },
            "content":{
                "text":"On pourra aussi noter que :"
            }
        },
        {
            "type":"list",
            "children":[
                {
                "type":"listItem",
                "attributes":{
                    "styles":[
                        "bullet"
                    ]
                },
                "children":[
                    {
                        "type":"paragraph",
                        "content":{
                            "text":"C\'est cool"
                        }
                    }
                ]
                },
                {
                "type":"listItem",
                "attributes":{
                    "styles":[
                        "bullet"
                    ]
                },
                "children":[
                    {
                        "type":"paragraph",
                        "attributes":{

                        },
                        "content":{
                            "text":"C\'est beau"
                        }
                    }
                ]
                }
            ]
        },
        {
            "type":"paragraph",
            "attributes":{

            },
            "content":{
                "text":"Nous conclurons avec cet exemple :"
            }
        },
        {
            "type":"pre",
            "content":{
                "text":"E = MC2",
                "annotations":[
                {
                    "type":"textStyle\/bold",
                    "range":{
                        "start":0,
                        "end":1
                    }
                }
                ]
            }
        }
    ]
}'
            );
        $doc = new Document($json);
		$this->assertEquals(
            '<h2>Ceci <i>est</i> un <b>titre</b></h2>' .
            '<h3>Ceci est un sous titre</h3>' .
            '<h4>Ceci est saoul titre</h4>' .
            '<p>Et voilà enfin un paragraphe. Ouf. Il était <b>temps</b>.</p>' .
            '<p>Listons les points positifs :</p>' .
            '<ol><li><p>C&#39;est cool</p></li>' .
            '<li><p>C&#39;est beau</p></li>' .
            '<li><ol><li><p>Même très beau</p></li></ol></li>' .
            '<li><p>C&#39;est génial</p></li></ol>' .
            '<p>On pourra aussi noter que :</p>' .
            '<ul><li><p>C&#39;est cool</p></li>' .
            '<li><p>C&#39;est beau</p></li></ul>' .
            '<p>Nous conclurons avec cet exemple :</p>' .
            '<pre><b>E</b> = MC2</pre>',
            $doc->render()
        );
    }
}