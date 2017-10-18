<?php
namespace BeGateway;

class Customer {
  protected $_customer_ip;
  protected $_customer_email;

  protected $_customer_first_name;
  protected $_customer_last_name;
  protected $_customer_address;
  protected $_customer_city;
  protected $_customer_country;
  protected $_customer_state;
  protected $_customer_zip;
  protected $_customer_phone;
  protected $_customer_birth_date = NULL;

  public function setIP($ip) {
    $this->_customer_ip = $this->_setNullIfEmpty($ip);
  }
  public function getIP() {
    return $this->_customer_ip;
  }

  public function setEmail($email) {
    $this->_customer_email = $this->_setNullIfEmpty($email);
  }
  public function getEmail() {
    return $this->_customer_email;
  }

  public function setFirstName($first_name) {
    $this->_customer_first_name = $this->_setNullIfEmpty($first_name);
  }
  public function getFirstName() {
    return $this->_customer_first_name;
  }

  public function setLastName($last_name) {
    $this->_customer_last_name = $this->_setNullIfEmpty($last_name);
  }
  public function getLastName() {
    return $this->_customer_last_name;
  }

  public function setAddress($address) {
    $this->_customer_address = $this->_setNullIfEmpty($address);
  }

  public function getAddress() {
    return $this->_customer_address;
  }

  public function setCity($city) {
    $this->_customer_city = $this->_setNullIfEmpty($city);
  }
  public function getCity() {
    return $this->_customer_city;
  }

  public function setCountry($country) {
    $this->_customer_country = $this->_setNullIfEmpty($country);
  }
  public function getCountry() {
    return $this->_customer_country;
  }

  public function setState($state) {
    $this->_customer_state = $this->_setNullIfEmpty($state);
  }
  public function getState() {
    return (in_array($this->_customer_country, array( 'US', 'CA'))) ? $this->_customer_state : null;
  }

  public function setZip($zip) {
    $this->_customer_zip = $this->_setNullIfEmpty($zip);
  }
  public function getZip() {
    return $this->_customer_zip;
  }

  public function setPhone($phone) {
    $this->_customer_phone = $this->_setNullIfEmpty($phone);
  }
  public function getPhone() {
    return $this->_customer_phone;
  }

  public function setBirthDate($birthdate) {
    $this->_customer_birth_date = $this->_setNullIfEmpty($birthdate);
  }
  public function getBirthDate() {
    return $this->_customer_birth_date;
  }

  private function _setNullIfEmpty(&$resource) {
    return (strlen($resource) > 0) ? $resource : null;
  }
}
?>
