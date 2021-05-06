<?php

class Person {
  public $firstName;
  public $lastName;
  public $gender;
  public $age;

  public function setName($a,$b) {
    $this->firstName = $a;
    $this->lastName = $b;
    return $this;
  }

  public function getName() {
    return $this->firstName . " " . $this->lastName;
  }

}

 ?>
