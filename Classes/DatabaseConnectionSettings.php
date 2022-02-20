<?php

namespace Classes;

/**
 * Initializes values and makes a DNS for the PDO object.
 */
class DatabaseConnectionSettings
{
    public string $host;
    public string $user;
    public string $pass;
    public ?int $port;
    public string $database;
    public string $dns;

    public function __construct(
        string $host = 'localhost',
        string $user = '',
        string $pass = '',
        string $database = '',
        ?int $port = 3306
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->port = $port;
        $this->dns = "mysql:host=$this->host;";
        $this->dns .= "port=$this->port;";
        $this->dns .= (!empty($this->database) ? 'dbname=' . $this->database . ';' : '');
        return $this;
    }
}
