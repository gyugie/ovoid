<?php

namespace Gyugie\OVO\Response;

class TransferInquiryResponse
{
    private $response;

    public function __construct($data)
    {
        $this->response = $data;
    }

    public function getTransferInquiryResponse()
    {
        return $this->response;
    }
}
