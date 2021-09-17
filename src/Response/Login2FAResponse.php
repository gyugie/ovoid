<?php

namespace Gyugie\Response;

class Login2FAResponse
{
    private $otp_ref_id;

    public function __construct($data)
    {
        $this->otp_ref_id = $data->otp_ref_id;
    }

    /**
     * get value of refId
     *
     * @return string
     */
    public function getOtpRefId()
    {
        return $this->otp_ref_id;
    }
}
