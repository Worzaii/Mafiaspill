<?php
if (!defined('THRUTT')) {
    die('No access! :(');
}
if (THRUTT == "Sperrederrp!") {

    class database
    {
        var $host;
        var $user;
        var $pass;
        var $database;
        var $last_query;
        var $result;
        var $con;
        var $num_queries = 0;
        var $start_time;
        var $last_error;

        function connect()
        {
            $this->con = mysqli_init();
            mysqli_ssl_set($this->con, "/var/www/mafia.werzaire.net/pemfiles/client-key.pem", "/var/www/mafia.werzaire.net/pemfiles/client-cert.pem", "", null, null);
            $this->con->real_connect("127.0.0.1", "mafia", "mafia", "mafia", null, null, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT);
            if (!$this->con) {
                $this->connection_error();
            }
            $this->con->set_charset("utf8mb4");
            return $this->con;
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
            $this->last_query = $query;
            $this->num_queries++;
            $this->result = $this->con->query($this->last_query) or $this->query_error();
            return $this->result;
        }

        function fetch_row($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_fetch_assoc($result);
        }

        function fetch_object($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_fetch_object($result);
        }

        function num_rows($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_num_rows($result);
        }

        function insert_id()
        {
            return mysqli_insert_id($this->con);
        }

        function connection_error()
        {
            $feil = 'MySQL error in file: ' . $_SERVER["REQUEST_URI"] . ': ' . mysqli_connect_error();
            error_log($feil);
            die('Database utilgjengelig! Sjekk loggen.');
        }

        function query_error()
        {
            global $obj;
            if (!isset($obj)) {
                $feil = 'MySQL error in file: ' . $_SERVER["REQUEST_URI"] . ': ' . $this->last_query;
            } else {
                $feil = 'MySQL error in file: ' . $_SERVER["REQUEST_URI"] . ' sent by session (' . $obj->user . ', ID: {' . $obj->id . '}): ' . mysqli_error($this->con) . '. ' . PHP_EOL . 'Query was: ' . $this->last_query;
            }
            error_log($feil);
            $this->last_error = "Det var en feil ved sp&oslash;rringen: &quote;" . $this->last_query . "&quote;(" . mysqli_errno($this->con) . ")Feil: " . mysqli_error($this->con);
            return false;
        }

        function fetch_single($result = 0)
        {
            if (!$result) {
                $result = $this->result;
            }
            return mysqli_result::fetch_field;
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
            return str_replace(array("<", ">", '"', "'", "\n"), array("&lt;", "&gt;", "&quot;", "&#39;", "<br>"), $in);
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
    }
}
