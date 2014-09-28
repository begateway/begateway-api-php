<?php
namespace eComCharge;

abstract class ChildTransaction extends ApiAbstract {
  protected $_parent_uid;
  public $money;

  public function __construct($shop_id,$shop_key) {
    $this->money = new Money();
    parent::__construct($shop_id,$shop_key);
  }

  public function setParentUid($uid) {
    $this->_parent_uid = $uid;
  }

  public function getParentUid() {
    return $this->_parent_uid;
  }

  protected function _buildRequestMessage() {
    $request = array(
      'request' => array(
        'parent_uid' => $this->getParentUid(),
        'amount' => $this->money->getCents()
      ),
    );

    Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

    return $request;
  }
}
?>
