<?php

declare(strict_types=1);

namespace Tests;

use BeGateway\Settings;
use PHPUnit\Framework\TestCase;

class AbstractTestCase extends TestCase
{
    const SHOP_ID = 361;
    const SHOP_KEY = 'b8647b68898b084b836474ed8d61ffe117c9a01168d867f24953b776ddcb134d';
    const SHOP_PUB_KEY = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArO7bNKtnJgCn0PJVn2X7QmhjGQ2GNNw412D+NMP4y3Qs69y6i5T/zJBQAHwGKLwAxyGmQ2mMpPZCk4pT9HSIHwHiUVtvdZ/78CX1IQJON/Xf22kMULhquwDZcy3Cp8P4PBBaQZVvm7v1FwaxswyLD6WTWjksRgSH/cAhQzgq6WC4jvfWuFtn9AchPf872zqRHjYfjgageX3uwo9vBRQyXaEZr9dFR+18rUDeeEzOEmEP+kp6/Pvt3ZlhPyYm/wt4/fkk9Miokg/yUPnk3MDU81oSuxAw8EHYjLfF59SWQpQObxMaJR68vVKH32Ombct2ZGyzM7L5Tz3+rkk7C4z9oQIDAQAB';
    const SHOP_ID_3D = 362;
    const SHOP_KEY_3D = '9ad8ad735945919845b9a1996af72d886ab43d3375502256dbf8dd16bca59a4e';
    const SHOP_PUB_KEY_3D = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxbrIzCgdcrI6hhXHjnQCxG8YPbHnTSFEnD195yg+w9IbHtIrOnRH+bQ+Ex+3GUTI9tARluyrShaZ4D+AxbMInyPGKrHx25kLzHJtfw0gpIPMhauLot2Qnn6DFzhwsF3couExlqq53+HV3CQAJlFd+uPWijDh6HhL/ljxXb7FCfTo/UxeNYDQwQIV6DXA/Y10/tx0eFeGzi4oJ+zstbbMMD4tBUL/GJ8CViqgyoqhXyP6/5yirNa4jvf9o+2LV4rm/7NjZfkDptkmd7DUJO9LqFdpT20wHoSz26FYRs3LYTCk1Abqjw9NYCqz1ADEkakRgGKacu/7JeQEovCAWOVRrwIDAQAB';

    public static function authorizeFromEnv(bool $threeds = false): void
    {
        if ($threeds) {
            $shop_id = getenv('SHOP_ID_3D');

            if (! $shop_id) {
                $shop_id = self::SHOP_ID_3D;
            }

            $shop_key = getenv('SHOP_SECRET_KEY_3D');

            if (! $shop_key) {
                $shop_key = self::SHOP_KEY_3D;
            }

            $shop_pub_key = getenv('SHOP_PUB_KEY_3D');

            if (! $shop_pub_key) {
                $shop_pub_key = self::SHOP_PUB_KEY_3D;
            }
        } else {
            $shop_id = getenv('SHOP_ID');

            if (! $shop_id) {
                $shop_id = self::SHOP_ID;
            }

            $shop_key = getenv('SHOP_SECRET_KEY');

            if (! $shop_key) {
                $shop_key = self::SHOP_KEY;
            }

            $shop_pub_key = getenv('SHOP_PUB_KEY');

            if (! $shop_pub_key) {
                $shop_pub_key = self::SHOP_PUB_KEY;
            }
        }

        Settings::$shopId = $shop_id;
        Settings::$shopKey = $shop_key;
        Settings::$shopPubKey = $shop_pub_key;
    }
}
