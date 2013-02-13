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
            '<h3>Ceci <i>est</i> un <b>titre</b></h3>' .
                '<h4>Ceci est un sous titre</h4>' .
                '<h5>Ceci est saoul titre</h5>' .
                '<p>Et vo<b>ilà e</b>nfin un paragraphe. Ouf. Il était temps.</p>' .
                '<p>Eh ouais !</p>'
            ,
            $doc->render()
        );
	}

    public function testRenderEmptyParagraph()
    {
        $json = json_decode('{"type":"document","children":[{"type":"paragraph","content":{"text":""}}]}');

        $doc = new Document($json);

        $this->assertEquals(
            '<p></p>'
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
            '<h3>Ceci <i>est</i> un <b>titre</b></h3>' .
            '<h4>Ceci est un sous titre</h4>' .
            '<h5>Ceci est saoul titre</h5>' .
            '<p>Et voilà enfin un paragraphe. Ouf. Il était <b>temps</b>.</p>' .
            '<p>Listons les points positifs :</p>' .
            '<ol><li><p>C&#039;est cool</p></li>' .
            '<li><p>C&#039;est beau</p></li>' .
            '<li><ol><li><p>Même très beau</p></li></ol></li>' .
            '<li><p>C&#039;est génial</p></li></ol>' .
            '<p>On pourra aussi noter que :</p>' .
            '<ul><li><p>C&#039;est cool</p></li>' .
            '<li><p>C&#039;est beau</p></li></ul>' .
            '<p>Nous conclurons avec cet exemple :</p>' .
            '<pre><b>E</b> = MC2</pre>',
            $doc->render()
        );
    }

    public function testRenderWithAnnotations()
   	{
           $json =
               json_decode('
                   {
                     "type":"document",
                     "children":
                     [
                       {
                         "type":"paragraph",
                         "content":
                         {
                           "text":"Eh ouais !",
                           "annotations":
                           [
                             {
                               "type":"textStyle\/bold",
                               "range":{
                                 "start":0,
                                 "end":1
                               }
                             },
                             {
                               "type":"link\/internal",
                               "data":{
                                 "link":"http://coin.com",
                                 "popup":true
                               },
                               "range":{
                                   "start":3,
                                   "end":4
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
               '<p><b>E</b>h <a href="http://coin.com" ' .
                    'class="popup">o</a>uais !</p>',
               $doc->render()
           );

           // TU issu du JS

           $json =
               json_decode(
                   '{"type":"document","children":[{"type":"paragraph","content":{"text":"popuptoono","annotations":[{"type":"link/external","range":{"start":0,"end":5},"data":{"title":"http://link1","popup":true}},{"type":"link/external","range":{"start":5,"end":8},"data":{"link":"http://link2","popup":true}},{"type":"link/external","range":{"start":8,"end":10},"data":{"link":"http://link3"}}]}}]}'
               );

           $doc = new Document($json);

           $this->assertEquals(
               '<p><a href="http://link1" class="popup">popup</a><a href="http://link2" class="popup">too</a><a href="http://link3">no</a></p>',
               $doc->render()
           );
   	}

    public function testListWithTextInsideListItems()
    {
        $json =
            json_decode(
                    '{"type":"document","children":[{"type":"list","content":{"text":""},"children":[{"type":"listItem","content":{"text":"this"},"children":[{"type":"list","content":{"text":""},"children":[{"type":"listItem","content":{"text":"is"},"children":[{"type":"list","content":{"text":""},"children":[{"type":"listItem","content":{"text":"a"},"children":[{"type":"list","content":{"text":""},"children":[{"type":"listItem","content":{"text":"test"},"attributes":{"styles":["bullet"]}}]}],"attributes":{"styles":["number"]}}]}],"attributes":{"styles":["bullet"]}}]}],"attributes":{"styles":["number"]}}]},{"type":"paragraph","content":{"text":"Yeah !"}}]}'
            );

        $doc = new Document($json);

        $this->assertEquals(
            '<ol>' .
                '<li>this' .
                '<ul>' .
                    '<li>is' .
                    '<ol>' .
                        '<li>a' .
                        '<ul>' .
                            '<li>test</li>' .
                        '</ul>' .
                        '</li>' .
                    '</ol>' .
                    '</li>' .
                '</ul>' .
                '</li>' .
            '</ol>' .
            '<p>Yeah !</p>',
            $doc->render()
        );
    }

}