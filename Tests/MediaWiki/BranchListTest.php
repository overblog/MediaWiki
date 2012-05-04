<?php

namespace Overblog\Tests\MediaWiki;

use Overblog\MediaWiki\BranchList;

/**
 * @group MediaWiki
 * @group Branch
 */

class BranchListTestCase extends \PHPUnit_Framework_TestCase
{
	public function testRender1()
	{
        $json =
            json_decode('
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
}'
            );

        $branch = new BranchList($json);

		$this->assertEquals(
            '<ol><li><p>C&#39;est cool</p></li>' .
            '<li><p>C&#39;est beau</p></li>' .
            '<li><p>Même très beau</p></li>' .
            '<li><p>C&#39;est génial</p></li></ol>',
            $branch->render()
        );
	}

	public function testRender2()
	{
        $json =
            json_decode('
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
}'
            );

        $branch = new BranchList($json);

		$this->assertEquals(
            '<ul><li><p>C&#39;est cool</p></li>' .
            '<li><p>C&#39;est beau</p></li></ul>',
            $branch->render()
        );
	}

	public function testRender3()
	{
        $json =
            json_decode('
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
}'
            );

        $branch = new BranchList($json);

		$this->assertEquals(
            '<ol><li><p>C&#39;est cool</p></li></ol>' .
            '<ul><li><p>C&#39;est beau</p></li></ul>',
            $branch->render()
        );
	}

	public function testRenderNested1()
	{
        $json =
            json_decode('
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
                "bullet", "bullet"
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
}'
            );

        $branch = new BranchList($json);

		$this->assertEquals(
            '<ul><li><p>C&#39;est cool</p></li>' .
            '<li><ul><li><p>C&#39;est beau</p></li></ul></li></ul>',
            $branch->render()
        );
	}


    /**
     * @group branch
     */

	public function testRenderNested2()
	{
        $json =
            json_decode('
{
    "type":"list",
    "children":[
        {
        "type":"listItem",
        "attributes":{
            "styles":[
                "bullet", "bullet"
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
        }
    ]
}'
            );

        $branch = new BranchList($json);

		$this->assertEquals(
            '<ul><li><ul><li><p>C&#39;est beau</p></li></ul></li>' .
            '<li><p>C&#39;est cool</p></li></ul>',
            $branch->render()
        );
	}
}