<?php

namespace Fukotaku\ArrayWork\Test;

use \PHPUnit\Framework\TestCase;
use \Fukotaku\ArrayWork\ArrayWork;

class ArrayWorkTest extends TestCase {

	public function testInitConstructor() {
		$ArrayWork = new ArrayWork();
		$this->assertEquals(20, $ArrayWork->getByPage());
		$this->assertEquals(array(), $ArrayWork->getData());
		return $ArrayWork;
	}

	public function testInitConstructorWithParam() {
		$ArrayWork = new ArrayWork(array(), 10);
		$this->assertEquals(10, $ArrayWork->getByPage());
	}

  public function testDataSort() {

		$ArrayWork = new ArrayWork(array(
		  array("id" => 2,
		        "name" => "test4"),
		  array("id" => 1,
		        "name" => "test5"),
		  array("id" => 3,
		        "name" => "test3")
		));
		$result = $ArrayWork->dataSort('id', 'DESC');

		$this->assertEquals(true, $result);

    $this->assertEquals(array(
			array("id" => 3,
						"name" => "test3"),
		  array("id" => 2,
		        "name" => "test4"),
		  array("id" => 1,
		        "name" => "test5")
		), $ArrayWork->getData());
  }

	public function testDataFilter() {

		$ArrayWork = new ArrayWork(array(
		  array("id" => 2,
		        "name" => "test3"),
		  array("id" => 1,
		        "name" => "test4"),
		  array("id" => 3,
		        "name" => "test5")
		));
		$result = $ArrayWork->dataFilter(array("ActionFilter" => "skip", "id"));

		$this->assertEquals(true, $result);

    $this->assertEquals(array(
			array("name" => "test3"),
		  array("name" => "test4"),
		  array("name" => "test5")
		), $ArrayWork->getData());
  }

	public function testGenerateTable() {

		$ArrayWork = new ArrayWork(array(
		  array("id" => 2,
		  "name" => "test4"),
		  array("id" => 1,
		  "name" => "test5"),
		  array("id" => 3,
		  "name" => "test3")
		));

    $this->assertNotEquals(false, $ArrayWork->generateTable());
		$this->assertTrue(is_string($ArrayWork->generateTable()));
  }

	/**
   * @depends testInitConstructor
   */
  public function testGetterSetter($ArrayWork) {
    $ArrayWork->setByPage(10);
		$ArrayWork->setPage(2);
		$ArrayWork->setData(array("test1", "test2"));

    $byPage = $ArrayWork->getByPage();
		$page = $ArrayWork->getPage();
		$url = $ArrayWork->getUrl();
		$data = $ArrayWork->getData();

    $this->assertEquals(10, $byPage);
		$this->assertEquals(2, $page);
		$this->assertEquals("#", $url);
		$this->assertEquals(array("test1", "test2"), $data);
  }

}
