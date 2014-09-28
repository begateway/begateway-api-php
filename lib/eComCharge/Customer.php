<?php
namespace eComCharge;

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

  public function setIP($ip) {
    $this->_customer_ip = $ip;
  }
  public function getIP() {
    return $this->_customer_ip;
  }

  public function setEmail($email) {
    $this->_customer_email = $email;
  }
  public function getEmail() {
    return $this->_customer_email;
  }

  public function setFirstName($first_name) {
    $this->_customer_first_name = $first_name;
  }
  public function getFirstName() {
    return $this->_customer_first_name;
  }

  public function setLastName($last_name) {
    $this->_customer_last_name = $last_name;
  }
  public function getLastName() {
    return $this->_customer_last_name;
  }

  public function setAddress($address) {
    $this->_customer_address = $address;
  }

  public function getAddress() {
    return $this->_customer_address;
  }

  public function setCity($city) {
    $this->_customer_city = $city;
  }
  public function getCity() {
    return $this->_customer_city;
  }

  public function setCountry($country) {
    $this->_customer_country = $country;
  }
  public function getCountry() {
    return $this->_customer_country;
  }

  public function setState($state) {
    $this->_customer_state = $state;
  }
  public function getState() {
    return (in_array($this->_customer_country, array( 'US', 'CA'))) ? $this->_customer_state : null;
  }

  public function setZip($zip) {
    $this->_customer_zip = $zip;
  }
  public function getZip() {
    return $this->_customer_zip;
  }

  public function setPhone($phone) {
    $this->_customer_phone = $phone;
  }
  public function getPhone() {
    return $this->_customer_phone;
  }
}
?>
