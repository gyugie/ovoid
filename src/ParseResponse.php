<?php

namespace Gyugie;

class ParseResponse
{
    /**
     * store Class
     *
     * @var array
     */
    public $storeClass = [
        OVOID::HEUSC_API . '/v3/user/accounts/otp'              => 'Gyugie\Response\Login2FAResponse',
        OVOID::HEUSC_API . '/v3/user/accounts/otp/validation'   => 'Gyugie\Response\Login2FAVerifyResponse',
        OVOID::HEUSC_API . '/v3/user/accounts/login'            => 'Gyugie\Response\LoginSecurityCodeResponse',
        OVOID::API_URL . 'v1.1/api/auth/customer/isOVO'         => 'Gyugie\Response\isOVOResponse',
        OVOID::API_URL . '/wallet/inquiry'                      => 'Gyugie\Response\Model\Balance'
    ];

    private $response;

    /**
     * Parse response init
     *
     * @param mixed  $chResult
     * @param string $url
     */
    public function __construct($chResult, $url)
    {
        $jsonDecodeResult = json_decode($chResult);

        //-- Cek apakah ada error dari OVO Response
        if (isset($jsonDecodeResult->message) && $jsonDecodeResult->message != '') {
            throw new \Gyugie\Exception\OvoidException($jsonDecodeResult->message . ' ' . $url);
        }

        $parts = parse_url($url);

        if ($parts['path'] == '/payment/orders/v1/list') {
            $this->response = new \Gyugie\Response\WalletTransactionResponse($jsonDecodeResult);
        } elseif (strpos($parts['path'], '/gpdm/ovo/ID/v1/billpay/get-denominations/') !== false) {
            $this->response = new \Gyugie\Response\DenominationsReponse($jsonDecodeResult);
        } else {
            $this->response = new $this->storeClass[$url]($jsonDecodeResult);
        }
    }

    /**
     * Get response following by class
     *
     * @return void
     */
    public function getResponse()
    {
        return $this->response;
    }
}
