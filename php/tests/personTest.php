<?php

use PHPUnit\Framework\TestCase;

final class PersonTests extends TestCase {
  public function testNewPerson() {
    $testPerson = new Person();
    $this->assertInstanceOf(Person::class, $testPerson);
  }
}
