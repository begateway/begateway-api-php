<?php
namespace BeGateway\PaymentMethod;

abstract class Base {

  public function getName() {
    $class_name = get_class($this);
    $name = str_replace(__NAMESPACE__ . "\\", '', $class_name);
    $name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
    return $name;
  }

  public function getParamsArray() {
    return array();
  }
}
?>
