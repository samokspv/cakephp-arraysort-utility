<?php

/**
 * Author: imsamurai <im.samuray@gmail.com>
 * Date: 18.06.2012
 * Time: 14:59:04
 * Format: http://book.cakephp.org/2.0/en/development/testing.html
 */
App::uses('ArraySort', 'ArraySort.Utility');

class ArraySortTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
	}

	public function testMultisort() {
		$expected = array(
			'item1' => array(
				'weight' => 4,
				'diff' => array(
					1 => 10,
					7 => -5,
					30 => 0
				)
			),
			'item2' => array(
				'weight' => 3,
				'diff' => array(
					1 => 10,
					7 => -5,
					30 => 0
				)
			),
			'item3' => array(
				'weight' => 3,
				'diff' => array(
					1 => 8,
					7 => -5,
					30 => 0
				)
			),
			'item4' => array(
				'weight' => 3,
				'diff' => array(
					1 => 8,
					7 => -10,
					30 => 0
				)
			),
			'item5' => array(
				'weight' => 3,
				'diff' => array(
					1 => 8,
					7 => -10,
					30 => -1
				)
			),
			'item6' => array(
				'weight' => 2,
				'diff' => array(
					1 => 3,
					7 => 4,
					30 => 5
				)
			),
			'item7' => array(
				'weight' => 1,
				'diff' => array(
					1 => 30,
					7 => 40,
					30 => 50
				)
			),
			'item8' => array(
				'weight' => 1,
				'diff' => array(
					1 => false,
					7 => false,
					30 => false
				)
			)
		);

		$test = $expected;

		$ashuffle = function (&$array) {
			$keys = array_keys($array);
			shuffle($keys);
			$array = array_merge(array_flip($keys), $array);
			return true;
		};

		$ashuffle($test);

		$params = array(
			'weight' => 'desc',
			'diff.1' => 'desc',
			'diff.7' => 'desc',
			'diff.30' => 'desc'
		);
		$this->assertNotSame($expected, $test);
		$this->assertSame($expected, ArraySort::multisort($test, $params));

		$expected = array(1, 2, 3, 4, 5, 6, 7, 8, 9);
		$test = $expected;
		shuffle($test);
		$params = 'asc';
		$this->assertNotSame($expected, $test);
		$this->assertSame($expected, ArraySort::multisort($test, $params));

		$expected = array(
			'item1' => 1,
			'item2' => 2,
			'item3' => 3,
			'item4' => 4,
			'item5' => 5
		);
		$test = $expected;
		$ashuffle($test);
		if ($test === $expected) {
			$ashuffle($test);
		}
		$params = 'asc';
		$this->assertNotSame($expected, $test);
		$this->assertSame($expected, ArraySort::multisort($test, $params));
	}

	/**
	 * Test multisort for objects
	 */
	public function testMultisortObjectByField() {
		$Object1 = (object)array(
					'weight' => 1
		);
		$Object2 = (object)array(
					'weight' => 2
		);
		$array = array(
			$Object1,
			$Object2
		);

		$result = array(
			$Object2,
			$Object1
		);

		$this->assertSame($result, ArraySort::multisort($array, array('weight' => 'DESC')));
	}

	/**
	 * Test multisort for objects
	 */
	public function testMultisortObjectByMethod() {
		$Object1 = new _ArraySortObject(1);
		$Object2 = new _ArraySortObject(2);
		$array = array(
			$Object1,
			$Object2
		);

		$result = array(
			$Object2,
			$Object1
		);

		$this->assertSame($result, ArraySort::multisort($array, array('getWeight' => 'DESC')));
	}

	/**
	 * Test multisort for objects
	 */
	public function testMultisortObjectByCallable() {
		$Object1 = new _ArraySortObject(1);
		$Object2 = new _ArraySortObject(2);
		$array = array(
			$Object1,
			$Object2
		);

		$result = array(
			$Object2,
			$Object1
		);

		$this->assertSame($result, ArraySort::multisort($array, array(
					array(
						'field' => function($Object) {
							return $Object->getWeight();
						},
						'direction' => 'desc'
					)
		)));
	}

}

//@codingStandardsIgnoreStart
if (!class_exists('_ArraySortObject')) {

	class _ArraySortObject {

		function __construct($weight) {
			$this->weight = $weight;
		}

		function getWeight() {
			return $this->weight;
		}

	}
	
}
//@codingStandardsIgnoreEnd
