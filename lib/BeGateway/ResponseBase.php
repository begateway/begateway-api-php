<?php
namespace BeGateway;

abstract class ResponseBase {

  protected $_response;
  protected $_responseArray;

  public function __construct($message){
    $this->_response = json_decode($message);
    $this->_responseArray = json_decode($message, true);
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

  public function getResponseArray() {
    return $this->_responseArray;
  }

}
?>
