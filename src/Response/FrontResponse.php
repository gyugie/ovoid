<?php

namespace Gyugie\OVO\Response;

class FrontResponse
{
    /**
     * Balance Model
     *
     * @var \Gyugie\Response\Model\Balance
     */
    private $balance;

    /**
     * Permission Model
     *
     * @var \Gyugie\Response\Model\Permission
     */
    private $permission;

    /**
     * Pofile Model
     *
     * @var \Gyugie\Response\Model\Profile
     */
    private $profile;

    public function __construct($data)
    {
        $this->balance = new \Gyugie\Response\Model\Balance($data->balance);
        $this->permission = new \Gyugie\Response\Model\Permission($data->permissions);
        $this->profile = new \Gyugie\Response\Model\Profile($data->profile);
    }

    /**
     * Get balance Model
     *
     * @return \Gyugie\Response\Model\Balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Get balance Model
     *
     * @return \Gyugie\Response\Model\Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Get balance Model
     *
     * @return \Gyugie\Response\Model\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
