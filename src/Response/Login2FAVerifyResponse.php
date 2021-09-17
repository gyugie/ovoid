<?php

namespace Gyugie\OVO\Response;

class Login2FAVerifyResponse
{
    private $otp_token;

    public function __construct($data)
    {
        $this->otp_token = $data->otp_token;
    }

    /**
     * Get the value of Mobile
     *
     * @return string
     */
    public function getOtpToken()
    {
        return $this->otp_token;
    }
}
