<?php

/**
 * Class MySqlConfig
 */
class MySqlConfig implements PDOConfig
{

    /**
     * @var
     */
    private $_username;

    /**
     * @var
     */
    private $_password;

    /**
     * @var
     */
    private $_db;

    /**
     * @var string
     */
    private $_host = 'localhost';

    /**
     * @var int
     */
    private $_port = 3306;

    /**
     * @var string
     */
    private $_charset = 'utf8';

    /**
     * MySqlConfig constructor.
     *
     * @param $username
     * @param $password
     * @param $db
     * @param $host
     * @param $port
     */
    public function __construct($username, $password, $db, $host, $port)
    {
        $this->_username = $username;
        $this->_password = $password;
        $this->_host = $host;
        $this->_port = $port;
        $this->_db = $db;
    }

    /**
     * @return string returns dsn string used for PDO connection
     */
    public function getDSN()
    {
        return sprintf('mysql:host=%s;dbname=%s;charset=%s;port=%s;connect_timeout=15', $this->_host, $this->_db, $this->_charset, $this->_port);
    }


    /**
     * @return mixed returns database username
     */
    public function getUsername()
    {
        return $this->_username;
    }

    /**
     * @return mixed returns database password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @return array specific PDO driver options/attributes
     */
    public function getDriverOptions()
    {
        return [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
    }

}