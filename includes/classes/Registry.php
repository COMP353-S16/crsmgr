<?php

/**
 * Class Registry
 * This is a singleton pattern class which returns database connection
 */
class Registry
{

    /**
     * @var PDO
     */
    private static $connection;

    /**
     * @var PDOConfig
     */
    private static $config;

    /**
     * @param PDOConfig $config set PDO configuration based on connection type, e.g. MySQL
     */
    public static function setConfig(PDOConfig $config)
    {
        self::$config = $config;

    }

    /**
     * @return PDO returns PDO object connection
     */
    public static function getConnection()
    {
        if (self::$connection === null)
        {
            if (self::$config === null)
            {
                throw new RuntimeException('No config set, cannot create connection');
            }
            self::$connection = null;
            try
            {
                self::$connection = new PDO(self::$config->getDSN(), self::$config->getUsername(), self::$config->getPassword(), self::$config->getDriverOptions());
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }

        }

        return self::$connection;
    }
}

