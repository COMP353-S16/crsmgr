<?php

/**
 * Interface PDOConfig
 */
interface PDOConfig
{

    /**
     * @return mixed
     */
    public function getDSN();

    /**
     * @return mixed
     */
    public function getUsername();

    /**
     * @return mixed
     */
    public function getPassword();

    /**
     * @return mixed
     */
    public function getDriverOptions();

}