<?php

use PHPUnit\Framework\TestCase;
use Gyugie\OVOID;
use Gyugie\Response\Login2FAResponse;
use Gyugie\Response\Login2FAVerifyResponse;
use Gyugie\Response\LoginSecurityCodeResponse;
use Gyugie\Response\WalletTransactionResponse;

final class OVOIDTest extends TestCase
{
	public function testPhoneNumber()
	{
		$ovo = new OVOID();

		$phone_number = $ovo->formatPhone('6289111111111');

        $this->assertTrue($phone_number === '089111111111');
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

    public function testSimulationAuthentication()
    {
        $device_id = (new OVOID())->generateUUIDV4();

        $credentials = [
            'device_id' => '0F871081-5246-4841-8CCF-BDBB96C13CA5', 
            'phone_number' => '089XXXXXXXX',
            'otp_ref_id' => 'b33947ee-9b86-43b2-b515-c211343fefb3',
            'otp_code' => '1234',
            'otp_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.NE5MekFicTBpd3FhV2o4Y1U3VnB6eVVRQ2VBMlVweHRSNkNIakxpMm0vYnQ4Q3h3NEhSeFpzTE40dGsrU1kwVDJhb3NwT3pYdDE0TWtvTCtPeXozamdMeDRhNTNxTlhvM1JGTllKdkVYejN3VXVyYUFjVEpLeXFjYjN',
            'security_code' => '111111',
            'auth_token' => 'eyJhbGciOiJSUzI1NiJ9.eyJleHBpcnlJbk1pbGxpU2Vjb25kcyI6NjA0ODAwMDAwLCJjcmVhdGVUaW1lIjoxNjMxODYwMDU3ODk4LCJzZWNyZXQiOiJod3Y3ZHFtdWMrekQ1MjBaRGVBemZvOWZpcHcwT1ZVVWhNU21vU1d1UzRsa1A4cVE3Nk1nRVY0TVJMcWdHVEN0WnVTUmxsWXhuaXNpaGJrWlBLL0pDVSt4Q2ROZjJBaFRVRWdzdDFqQUxOMnhFZ1IvN2VGUEZUSFFZUjd2N3g4U0txQU1BRmNuZ0hpZkc4VU0vQ3hwOEIxbFRvVXNrWlZQSzdBK0dtQURjR3p0NFE4SXVpNGpvQkI3U1dKUTNZTkh6NzZJdG1PSDc4dEJIYXhKZmhMRFNBUnMyamc0OEtJbXNlVFJURXRJcTRZOFpsdzJuSXpQWnVUNklpSWhRQ0x5ZktGQmZNN3NlUzd0N25NUVlaM0ZwUE5JVDFoa044bFZBVnc5YXQ5RkJmUGk1azhibXl6bDdseU5PMHN0elpYN0JacEk5NEpYT3BNU24zTnJXUDhYNEk2Z2VSbHFFamZjdmN1dCsvdUtPdlE9In0.FPFrAT_Qvb3fkPFkcFdJV1aF_4wil2a9ZpE3GRbrON2OeE3bFRe6n0R_DDKjZZeMubKPuDzBvysjSHCpyUywX_gygm0f8durrkKZJXjw-JuA6oTKYwT-9kN3YsS5UvuztQQKz9PI66NoaNOIGa-5iWnT4QEgdPf6svqMXZ3eI8NmVVpR41g6vcsVMw43o5BohHY2yX1yb4g6U1s56dHXKXZHszEbDcO6Ij14c9c-Ni6lrxTVs3_S-2TTiwv8mWkMwNpUzAcVr2BpGuztjhNks2gwa1BdUmiCJk4AauDF1efmSlBLKb4hVGmn-5rmmJBhTejGCf5Zfk9av3yGGxPWyA'
        ];

        $ovoid = new OVOID($credentials['device_id'], $credentials['auth_token']);

        // $login2FA = $ovoid->login2FA($credentials['phone_number']);
        // print_r($login2FA);exit;

        // $login2FAVerify = $ovoid->login2FAVerify($credentials['otp_ref_id'], $credentials['otp_code'], $credentials['phone_number']);
        // print_r($login2FAVerify);exit;

        // $loginSecurityCode = $ovoid->loginSecurityCode($credentials['security_code'], $credentials['phone_number'], $credentials['otp_token']);
        // print_r($loginSecurityCode);exit;

        // $hsitory_transaction = $ovoid->getWalletTransaction(1, 1000);
        // print_r($hsitory_transaction);exit;

        
    }
} 