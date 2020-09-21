## Changelog

### 4.4.0

  * Add headers setup in cURL requests
  * Add `X-Api-Version: 2` header to request payment tokens
  * Remove `version` param in payment token requests

### 4.3.0

  * Add `attempts` to get a payment token
  * Add connect and read cURL timeouts
  * Add `be` locale

### 4.2.1

  * Add `ka` locale

### 4.1.10

  * Add `Product` API

### 4.1.9

  * Add `setMeta`/`getMeta` to `AdditionalData` class to save own data
  * Add `setEncryption` to pass encrypted card data section

### 3.0.0

  * Fix class naming
  * Use 2.1 version of payment page

### 2.6.0

  * Add `expired_at` parameter to `GetPaymentToken`

### 2.5.3

  * Add customer birth date

### 2.5.2

  * Add `erip` payment method
  * Support V2.0 of payment page

### 2.2.3

  * Add default `false` value to `setSkip3D` function call

### 2.2.1

  * Refactor `Money` to handle amount and cents independently when a
    currency set

### 2.2.0

  * Rename ``GetPaymentPageToken`` class to ``GetPaymentToken``
