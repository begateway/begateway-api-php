<?php
namespace eComCharge;

class GetPaymentPageToken extends ApiAbstract {
  public $customer;
  public $money;
  protected $_description;
  protected $_tracking_id;
  protected $_success_url;
  protected $_decline_url;
  protected $_fail_url;
  protected $_cancel_url;
  protected $_notification_url;
  protected $_transaction_type;
  protected $_language;
  protected $_readonly;
  protected $_hidden;

  public function __construct($shop_id, $shop_key) {
    $this->customer = new Customer();
    $this->money = new Money();
    $this->setPaymentTransactionType();
    $this->_language = Language::getDefaultLanguage();
    $this->_readonly = array();
    $this->_hidden = array();

    parent::__construct($shop_id, $shop_key);
  }
  protected function _endpoint() {
    return $this->_pp_service_url . '/ctp/api/checkouts';
  }

  protected function _buildRequestMessage() {
    $request = array(
      'checkout' => array(
        'transaction_type' => $this->getTransactionType(),
        'order' => array(
          'amount' => $this->money->getCents(),
          'currency' => $this->money->getCurrency(),
          'description' => $this->getDescription(),
          'tracking_id' => $this->getTrackingId()
        ),
        'settings' => array(
          'notification_url' => $this->getNotificationUrl(),
          'success_url' => $this->getSuccessUrl(),
          'decline_url' => $this->getDeclineUrl(),
          'fail_url' => $this->getFailUrl(),
          'cancel_url' => $this->getCancelUrl(),
          'language' => $this->getLanguage(),
          'customer_fields' => array(
            'read_only' => $this->getReadonlyFields(),
            'hidden' => $this->getHiddenFields()
          )
        ),
        'customer' => array(
          'email' => $this->customer->getEmail(),
          'first_name' => $this->customer->getFirstName(),
          'last_name' => $this->customer->getLastName(),
          'country' => $this->customer->getCountry(),
          'city' => $this->customer->getCity(),
          'state' => $this->customer->getState(),
          'zip' => $this->customer->getZip(),
          'address' => $this->customer->getAddress(),
          'phone' => $this->customer->getPhone()
        )
      )
    );

    Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

    return $request;
  }

  public function submit() {
    return new ResponseCheckout($this->_remoteRequest());
  }

  public function getToken() {
    return $this->getResponse()->checkout->token;
  }

  public function setDescription($description) {
    $this->_description = $description;
  }
  public function getDescription() {
    return $this->_description;
  }

  public function setTrackingId($tracking_id) {
    $this->_tracking_id = $tracking_id;
  }
  public function getTrackingId() {
    return $this->_tracking_id;
  }

  public function setNotificationUrl($notification_url) {
    $this->_notification_url = $notification_url;
  }
  public function getNotificationUrl() {
    return $this->_notification_url;
  }

  public function setSuccessUrl($success_url) {
    $this->_success_url = $success_url;
  }
  public function getSuccessUrl() {
    return $this->_success_url;
  }

  public function setDeclineUrl($decline_url) {
    $this->_decline_url = $decline_url;
  }
  public function getDeclineUrl() {
    return $this->_decline_url;
  }

  public function setFailUrl($fail_url) {
    $this->_fail_url = $fail_url;
  }
  public function getFailUrl() {
    return $this->_fail_url;
  }
  public function setCancelUrl($cancel_url) {
    $this->_cancel_url = $cancel_url;
  }
  public function getCancelUrl() {
    return $this->_cancel_url;
  }

  public function setAuthorizationTransactionType() {
    $this->_transaction_type = 'authorization';
  }

  public function setPaymentTransactionType() {
    $this->_transaction_type = 'payment';
  }

  public function getTransactionType() {
    return $this->_transaction_type;
  }

  public function setLanguage($language_code) {
    if (in_array($language_code, Language::getSupportedLanguages())) {
      $this->_language = $language_code;
    }else{
      $this->_language = Language::getDefaultLanguage();
    }
  }

  public function getLanguage() {
    return $this->_language;
  }


  public function getReadonlyFields() {
    return $this->_readonly;
  }
  public function getHiddenFields() {
    return $this->_hidden;
  }

  public function setFirstNameReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'first_name');
  }
  public function unsetFirstNameReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('first_name'));
  }
  public function setLastNameReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'last_name');
  }
  public function unsetLastNameReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('last_name'));
  }
  public function setEmailReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'email');
  }
  public function unsetEmailReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('email'));
  }
  public function setAddressReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'address');
  }
  public function unsetAddressReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('address'));
  }
  public function setCityReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'city');
  }
  public function unsetCityReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('city'));
  }
  public function setStateReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'state');
  }
  public function unsetStateReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('state'));
  }
  public function setZipReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'zip');
  }
  public function unsetZipReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('zip'));
  }
  public function setPhoneReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'phone');
  }
  public function unsetPhoneReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('phone'));
  }
  public function setCountryReadonly(){
    $this->_readonly = self::_searchAndAdd($this->_readonly, 'country');
  }
  public function unsetCountryReadonly(){
    $this->_readonly = array_diff($this->_readonly, array('country'));
  }

  public function setPhoneHidden() {
    $this->_hidden = self::_searchAndAdd($this->_hidden, 'phone');
  }

  public function unsetPhoneHidden() {
    $this->_hidden = array_diff($this->_hidden, array('phone'));
  }

  public function setAddressHidden() {
    $this->_hidden = self::_searchAndAdd($this->_hidden, 'address');
  }

  public function unsetAddressHidden() {
    $this->_hidden = array_diff($this->_hidden, array('address'));
  }

  private function _searchAndAdd($array, $value) {
    // search for $value in $array
    // if not found, adds $value to $array and returns $array
    // otherwise returns not altered $array
    $arr = $array;
    if (!in_array($value, $arr)) {
      array_push($arr, $value);
    }
    return $arr;
  }

}
?>
