<?php

namespace Gyugie\OVO\Response;

class isOVOResponse
{
    private $resp;

    public function __construct($decoded)
    {
        $this->resp = $decoded;
    }

    /**
     *
     * @return array
     */
    public function getIsOVOResponse()
    {
        return $this->resp;
    }
}
