<?php
namespace eComCharge;

class ResponseCheckout extends ResponseBase {

  public function isSuccess() {
    return is_object($this->getResponse()->checkout);
  }

  public function isError() {
    return parent::isError() || $this->getResponse()->checkout->status == 'error';
  }

  public function getMessage() {
    if (isset($this->getResponse()->message)) {
      return $this->getResponse()->message;
    }elseif (isset($this->getResponse()->response) && isset($this->getResponse()->response->message)) {
      return $this->getResponse()->response->message;
    }elseif ($this->isError()) {
      return $this->_compileErrors();
    }else{
      return '';
    }
  }

  public function getToken() {
    return $this->getResponse()->checkout->token;
  }

  private function _compileErrors() {
    return 'there are errors in request parameters';
  }

}
?>
