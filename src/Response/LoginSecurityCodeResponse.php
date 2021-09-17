<?php

namespace Gyugie\Response;

class LoginSecurityCodeResponse
{
    private $status;
    private $auth_token;

    public function __construct($data)
    {
        $this->auth_token = $data->auth_token;
        $this->status = $data->status;
    }

    /**
     * Get the value of token
     *
     */
    public function getAuthorizationToken()
    {
        return $this->auth_token;
    }

    /**
     * Get the value of tokenSeed
     */
    public function getStatus()
    {
        return $this->status;
    }
}
