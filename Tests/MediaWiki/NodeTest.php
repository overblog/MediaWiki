<?php

namespace Overblog\Tests\MediaWiki;

use Overblog\MediaWiki\Node;

/**
 * @group MediaWiki
 */

class NodeTestCase extends \PHPUnit_Framework_TestCase
{
	public function testGetClassFromNode()
	{
		$this->assertEquals(
			'Overblog\MediaWiki\LeafHeading',
			Node::getClassFromNode(Node::NODE_HEADING)
		);
		$this->assertEquals(
			'Overblog\MediaWiki\LeafParagraph',
			Node::getClassFromNode(Node::NODE_PARAGRAPH)
		);
		$this->assertEquals(
			'Overblog\MediaWiki\LeafPre',
			Node::getClassFromNode(Node::NODE_PRE)
		);
		$this->assertEquals(
			'Overblog\MediaWiki\BranchList',
			Node::getClassFromNode(Node::NODE_LIST)
		);
		$this->assertEquals(
			'Overblog\MediaWiki\BranchListItem',
			Node::getClassFromNode(Node::NODE_LIST_ITEM)
		);
	}
}