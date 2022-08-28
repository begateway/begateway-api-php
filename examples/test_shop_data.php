<?php

// Shop ID issued by your payment provider
BeGateway\Settings::$shopId = 361;

// Shop secret key issued by your payment provider
BeGateway\Settings::$shopKey = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';

// Shop secret key issued by your payment provider
BeGateway\Settings::$shopPubKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArO7bNKtnJgCn0PJVn2X7QmhjGQ2GNNw412D+NMP4y3Qs69y6i5T/zJBQAHwGKLwAxyGmQ2mMpPZCk4pT9HSIHwHiUVtvdZ/78CX1IQJON/Xf22kMULhquwDZcy3Cp8P4PBBaQZVvm7v1FwaxswyLD6WTWjksRgSH/cAhQzgq6WC4jvfWuFtn9AchPf872zqRHjYfjgageX3uwo9vBRQyXaEZr9dFR+18rUDeeEzOEmEP+kp6/Pvt3ZlhPyYm/wt4/fkk9Miokg/yUPnk3MDU81oSuxAw8EHYjLfF59SWQpQObxMaJR68vVKH32Ombct2ZGyzM7L5Tz3+rkk7C4z9oQIDAQAB';

// Checkout URL of your payment provider. Confirm it with support team or refer
// to your payment provider API documentation
BeGateway\Settings::$checkoutBase = 'https://checkout.begateway.com';

// Gateway URL of your payment provider. Confirm it with support team or refer
// to your payment provider API documentation
BeGateway\Settings::$gatewayBase = 'https://demo-gateway.begateway.com';

// API URL of your payment provider. Confirm it with support team or refer
// to your payment provider API documentation
BeGateway\Settings::$apiBase = 'https://api.begateway.com';
