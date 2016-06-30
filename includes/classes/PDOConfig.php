<?php

interface PDOConfig
{
    public function getDSN();

    public function getUsername();

    public function getPassword();

    public function getDriverOptions();

}