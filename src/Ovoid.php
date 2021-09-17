<?php

namespace Gyugie;

use Gyugie\HTTP\Curl;
use Gyugie\Meta\Meta;
use Gyugie\Meta\ActionMark;

/**
 * OVOID
 *
 * @author gyugie <mugypleci@gmail.com>
 * @package library
 * @license MIT
 */
class OVOID
{
    /**
     * Base OVO ENDPOINT
     */
    const API_URL = "https://api.ovo.id";
    const HEUSC_API = "https://agw.heusc.id";
    const AGW_API = "https://agw.ovo.id";

    /**
     * Authorization Token
     *
     * @var string
     */
    private string $authToken;

    private string $device_id;

    private $headers = [
        "content-type: ". META::CONTENT_TYPE, 
        "app-id: " . META::APP_ID, 
        "app-version: " . META::APP_VERSION, 
        "os: " . META::OS_NAME, 
        "user-agent: " . META::USER_AGENT, 
        'client-id: ' . META::CLIENT_ID
    ];

    public function __construct($deviceId = null, $authToken = null)
    {
        if($deviceId) {
            $this->device_id = $deviceId;
        }

        if($authToken) {
            $this->authToken = $authToken;
        }
    }


    /*
    * generateUUIDV4
    * generate random UUIDV4 for device ID

    */
    public function generateUUIDV4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return strtoupper(vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4)));
    }

    /**
     * get device id
     * @return string device_id
     */
    public function getDeviceId() : string
    {
        return $this->device_id;
    }

    /**
     * re formating phone number tanpa kode negara deault
     * @return string phone
     */
    public function formatPhone($phoneNumber, $areacode = '')
    {
        $local_country_code = substr_replace($phoneNumber, $areacode, 2);

        if ((int) $local_country_code === 62) {
            return 0 . substr_replace($phoneNumber, $areacode, 0, 2);
        };

        return 0 . substr_replace($phoneNumber, $areacode, 0, 1);
    }

    /**
     * login2FA
     *
     * Proses login yang harus dilalui yaitu dengan ini, hasil respon berupa
     * refId yang akan digunakan selanjutnya
     *
     * @param  string                            $mobile_phone
     * @return \Gyugie\Response\Login2FAResponse
     */
    public function login2FA($mobile_phone)
    {
        $ch = new Curl;

        $payload = [
            "msisdn" => $this->formatPhone($mobile_phone),
            "device_id" => $this->device_id
        ];

        return $ch->post(OVOID::HEUSC_API . "/v3/user/accounts/otp", $payload, $this->headers)->getResponse()->getOtpRefId();
    }

    /**
     * Verify login
     *
     * @param  string                                  $refId
     * @param  string                                  $verificationCode 4 digit
     * @param  string                                  $mobilePhone      phone number Example: 085289...
     * @return \Gyugie\Response\Login2FAVerifyResponse
     */
    public function login2FAVerify($refId, $verificationCode, $mobilePhone)
    {
        $ch = new Curl;

        $payload = [
            "msisdn" => $this->formatPhone($mobilePhone),
            "device_id" => $this->device_id, 
            "otp_code" => $verificationCode, 
            "reff_id" => $refId
        ];

        return $ch->post(OVOID::HEUSC_API . '/v3/user/accounts/otp/validation', $payload, $this->headers)->getResponse()->getOtpToken();
    }

    /**
     * Login Secruity Code
     *
     * @param  string                                     $securityCode      MAX is 6 digit
     * @param  string                                     $updateAccessToken
     * @return \Gyugie\Response\LoginSecurityCodeResponse
     */
    public function loginSecurityCode($securityCode, $phoneNumber, $otp_token)
    {
        $ch = new Curl;

        $payload = [
            "security_code" => $securityCode, 
            "msisdn" => $this->formatPhone($phoneNumber), 
            "device_id" => $this->device_id, 
            "otp_token" => $otp_token
        ];

        return $ch->post(OVOID::HEUSC_API . '/v3/user/accounts/login', $payload, $this->headers)->getResponse()->getAuthorizationToken();
    }

    /**
     * Wallet Transaction
     *
     * @param  int                                        $page  halaman ke berapa
     * @param  int                                        $limit berapa kontent dalam 1 page
     * @return \Gyugie\Response\WalletTransactionResponse
     */
    public function getWalletTransaction($page, $limit = 10)
    {
        $ch = new Curl;

        return $ch->get(
            OVOID::AGW_API . "/payment/orders/v1/list?limit={$limit}&page={$page}",
            null,
            $this->_aditionalHeader()
        )->getResponse()->getData();
    }

    /**
     * check apakah OVO
     *
     * Hasil dari method ini untuk mengecek atau memverifikasi apakah sudah benar
     * yang mau ditransfer benar akun tersebut apa tidak
     * silahkan di cek dengan getIsOVOResponse
     *
     * @param  int                            $totalAmount jumlah yang dikirim
     * @param  string                         $mobilePhone nomor yang dituju
     * @return \Gyugie\Response\isOVOResponse
     */
    public function isOVO($mobilePhone)
    {
        $ch = new Curl;
        $payload = [
            'amount' => 10000,
            'mobile' => $mobilePhone
        ];

        return $ch->post(OVOID::API_URL . '/v1.1/api/auth/customer/isOVO', $payload, $this->_aditionalHeader())->getResponse();
    }

    /**
     * Add Authorization token
     *
     * @return array
     */
    private function _aditionalHeader()
    {
        $data = [
            'authorization: Bearer ' . $this->authToken
        ];

        return array_merge($data, $this->headers);
    }

}
