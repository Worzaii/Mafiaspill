<?php

namespace DatabaseObject {

    if (!defined('THRUTT')) {
        die('No access! :(');
    }
    if (THRUTT == "Sperrederrp!") {
        class database
        {
            protected $mysql;
            protected $host = "127.0.0.1";
            protected $user = "mafia";
            protected $pass = "mafia";
            protected $database = "mafia";
            var $last_query;
            public $result;
            public $con;
            var $num_queries = 0;
            var $start_time;
            protected $last_error;
            protected $key = "/var/www/mafia.werzaire.net/pemfiles/client-key.pem";
            protected $cert = "/var/www/mafia.werzaire.net/pemfiles/client-cert.pem";
            protected $ca = "/var/www/mafia.werzaire.net/pemfiles/ca.pem";
            public static $connection;

            function connect()
            {
                $connection = mysqli_init();
                //mysqli_ssl_set($connection, $this->key, $this->cert, $this->ca, null, null);
                if ($connection->real_connect(
                    $this->host,
                    $this->user,
                    $this->pass,
                    $this->database,
                    3306,
                    null,
                    MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
                )) {
                    $this->con = $connection;
                    return true;
                } else {
                    return false;
                }
            }

            function disconnect()
            {
                if ($this->con) {
                    mysqli_close($this->con);
                    $this->con = 0;
                    return 1;
                } else {
                    return 0;
                }
            }

            function query($query)
            {
                sql_log("Running a query: " . $query);
                $this->last_query = $query;
                $this->num_queries++;
                $this->result = $this->con->query($this->last_query);
                return $this->result;
            }

            function fetch_row($result = 0)
            {
                if (!$result) {
                    $result = $this->result;
                }
                return mysqli_fetch_assoc($result);
            }

            function fetch_object()
            {
                return $this->result->fetch_object();
            }

            function num_rows()
            {
                return $this->result->num_rows;
            }

            function insert_id()
            {
                return mysqli_insert_id($this->con);
            }

            function connection_error()
            {
                $feil = 'MySQL error in file: ' . $_SERVER["REQUEST_URI"] . ': ' . $this->con->connection_error();
                error_log($feil);
                return false;
            }

            function query_error()
            {
                global $obj;
                if (!isset($obj)) {
                    $feil = 'MySQL error in file: ' . $_SERVER["REQUEST_URI"] . ': ' . $this->last_query;
                } else {
                    $feil = 'MySQL error in file: ' . $_SERVER["REQUEST_URI"] . ' sent by session (' . $obj->user . ', ID: {' . $obj->id . '}): ' . mysqli_error(
                            $this->con
                        ) . '. ' . PHP_EOL . 'Query was: ' . $this->last_query;
                }
                error_log($feil);
                $this->last_error = "Det var en feil ved spørringen: &quote;" . $this->last_query . "&quote;(" . mysqli_errno(
                        $this->con
                    ) . ")Feil: " . mysqli_error($this->con);
                return false;
            }

            function get_last_error()
            {
                return $this->last_error;
            }

            function mymicro()
            {
                $m = explode(" ", microtime());
                return $m[0] + $m[1];
            }

            function clock_start()
            {
                $this->start_time = $this->mymicro();
            }

            function clock_end()
            {
                $t = $this->mymicro();
                return round($t - $this->start_time, 7);
            }

            function clean_input($in)
            {
                $in = stripslashes($in);
                return str_replace(
                    array("<", ">", '"', "'", "\n"),
                    array("&lt;", "&gt;", "&quot;", "&#39;", "<br>"),
                    $in
                );
            }

            function clean_input_nohtml($in)
            {
                $in = stripslashes($in);
                return str_replace(array("'"), array("&#39;"), $in);
            }

            function slash($in)
            {
                return addslashes($in);
            }

            function escape($text)
            {
                return mysqli_real_escape_string($this->con, $text);
            }

            function affected_rows()
            {
                return mysqli_affected_rows($this->con);
            }

            function PDO_query()
            {
            }
        }
    }
}
