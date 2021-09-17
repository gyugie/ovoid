<?php

use PHPUnit\Framework\TestCase;
use Gyugie\OVOID;
use Gyugie\Response\Login2FAResponse;
use Gyugie\Response\Login2FAVerifyResponse;
use Gyugie\Response\LoginSecurityCodeResponse;
use Gyugie\Response\WalletTransactionResponse;
use Gyugie\Response\Model\Balance;

final class OVOIDTest extends TestCase
{
	public function testPhoneNumber()
	{
		$ovo = new OVOID();

		$phone_number = $ovo->formatPhone('6289XXXXXXXX');

        $this->assertTrue($phone_number === '089XXXXXXXX');
	}

    public function testSetDeviceId()
    {
        $device_id = (new OVOID())->generateUUIDV4();
        
        $this->assertTrue($device_id === (new OVOID($device_id))->getDeviceId());
    }

    public function testLogin2FAResponse()
    {
        $data = <<<JSON
		{
            "response_code": "OV00000",
            "otp_ref_id": "8b7849c1-b4bd-43d5-8a4e-06e3d55c45ae",
            "type": "LOGIN",
            "length": 4,
            "reff_type": "OTP"
		}
JSON;
        $loginToken = (new Login2FAResponse(json_decode($data)))->getOtpRefId();

        $this->assertEquals("8b7849c1-b4bd-43d5-8a4e-06e3d55c45ae", $loginToken);
    }

    public function testlogin2FAVerify()
    {
        $data = <<<JSON
		{
            "response_code": "OV00000",
            "otp_ref_id": "8b7849c1-b4bd-43d5-8a4e-06e3d55c45ae",
            "type": "LOGIN",
            "otp_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.ZjFScElNWmNmTGtTREJ1T25xd21od0hoTnRtNDdjWFI2Z1FLdmIxN0JuNTFyYjlIUWd4cUdxZ3g2N1lpRmlqQlk5VE5rV3JxZmlvSlRNK1dOKzdFVjhwdk0wZWRYdmRkZXdTVG1wUjhqOTlBVnJOaE5Cc1lYSG5MVi8"
        }
JSON;

        $loginOtpToken = (new Login2FAVerifyResponse(json_decode($data)))->getOtpToken();

        $this->assertEquals("eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.ZjFScElNWmNmTGtTREJ1T25xd21od0hoTnRtNDdjWFI2Z1FLdmIxN0JuNTFyYjlIUWd4cUdxZ3g2N1lpRmlqQlk5VE5rV3JxZmlvSlRNK1dOKzdFVjhwdk0wZWRYdmRkZXdTVG1wUjhqOTlBVnJOaE5Cc1lYSG5MVi8", $loginOtpToken);
    }

    public function testLoginSecurityCode()
    {
        $data = <<< JSON
        {
            "response_code": "OV00000",
            "status": "success",
            "auth_token": "eyJhbGciOiJSUzI1NiJ9.-P8GSdEyug83x3yiOB0wyvs6pS9vKvPZC58KF---kunVvhSFia21g"
        }
        JSON;

        $securityCode = (new LoginSecurityCodeResponse(json_decode($data)))->getAuthorizationToken();

        $this->assertEquals("eyJhbGciOiJSUzI1NiJ9.-P8GSdEyug83x3yiOB0wyvs6pS9vKvPZC58KF---kunVvhSFia21g", $securityCode);
    }

    public function testGetResponseHistoryTransactions()
    {
        $data = <<<JSON
             {"data":{"status":200,"orders":[{"complete":[{"merchant_invoice":"SPR-1933669835","merchant_id":"20796","merchant_name":"TOKOPEDIA","transaction_date":"2021-04-19","transaction_time":"18:11:04","card_no":"800945xxxxxx6001","transaction_amount":18672.00,"transaction_fee":0,"transaction_amount_text":"0","ovo_earn":0,"ovo_used":18672,"emoney_used":0.00,"emoney_topup":0,"emoney_bonus":0,"emoney_used_text":"0","emoney_topup_text":"0","emoney_bonus_text":"0","desc1":"TOKOPEDIA","desc2":"Pembayaran","desc3":"","status":"SUCCESS","ui_type":1,"transaction_type":"FINANCIAL","transaction_type_id":7,"icon_url":"20796","category_id":26,"category_name":"Online Shopping"}],"pending":[]}],"message":""},"response_code":"OV00000","response_message":"Success","response_version":"1"}        
        JSON;

        $securityCode = (new WalletTransactionResponse(json_decode($data)))->getData();

        $this->assertEquals(
            json_decode($data)->data,
            $securityCode
        );
    }

    public function testGetBalance()
    {
        $data = <<<JSON
        {"status":200,"data":{"001":{"card_balance":1000,"card_no":"800XXXXX","payment_method":"OVO Cash"},"600":{"card_balance":1000,"card_no":"800XXXXX","payment_method":"OVO"}},"message":""}
        JSON;

        $balance = (new Balance(json_decode($data)))->getCardBalance('OVO');
        
        $this->assertTrue(1000 === $balance);

    }

    public function testSimulationAuthentication()
    {
        $device_id = (new OVOID())->generateUUIDV4();

        $credentials = [
            'device_id' => '0F871081-5246-4841-8CCF-BDBB96C13CA5', 
            'phone_number' => '08XXXXXXXXX',
            'otp_ref_id' => '54359b48-3aec-4984-846a-XXXXX',
            'otp_code' => '0879',
            'otp_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.XXX',
            'security_code' => '987456',
            'auth_token' => 'eyJhbGciOiJSUzI1NiJ9.XXX'
        ];

        // $ovoid = new OVOID($credentials['device_id'], $credentials['auth_token']);

        // $login2FA = $ovoid->login2FA($credentials['phone_number'])->getOtpRefId();
        // print_r($login2FA);exit;

        // $login2FAVerify = $ovoid->login2FAVerify($credentials['otp_ref_id'], $credentials['otp_code'], $credentials['phone_number'])->getOtpToken();
        // print_r($login2FAVerify);exit;

        // $loginSecurityCode = $ovoid->loginSecurityCode($credentials['security_code'], $credentials['phone_number'], $credentials['otp_token'])->getAuthorizationToken();
        // print_r($loginSecurityCode);exit;

        // $hsitory_transaction = $ovoid->getWalletTransaction(1, 1000)->getData();
        // print_r($hsitory_transaction);exit;
        
        $this->assertTrue(true);
    }
} 