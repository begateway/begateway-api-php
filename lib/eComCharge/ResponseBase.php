<?php
namespace eComCharge;

abstract class ResponseBase {

  protected $_response;

  public function __construct($message){
    $this->_response = json_decode($message);
  }
  public abstract function isSuccess();

  public function isError() {
    if (!is_object($this->getResponse()))
      return true;

    if (isset($this->getResponse()->errors))
      return true;

    if (isset($this->getResponse()->response))
      return true;

    return false;
  }

  public function isValid() {
    return !($this->_response === false || $this->_response == null);
  }

  public function getResponse() {
    return $this->_response;
  }

}
?>
