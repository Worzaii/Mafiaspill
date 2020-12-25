<?php

namespace UserObject {

    include "Rank.php";

    use PDO;
    use UserObject\Rank;

    class User
    {
        public $id;
        public $user;
        public $mail;
        public $image;
        public $profile;
        public $family;
        public $bank;
        public $hand;
        public $city;
        public $weapon;
        public $bullets;
        public $health;
        public $points;
        public $exp;
        public $status;
        public $support;
        public $ip;
        public $regip;
        public $hostname;
        public $reghostname;
        public $lastactive;
        public $forceout;
        public $regstamp;
        public $picmaker;
        public PDO $db;
        public $rank;
        public $rankname;
        public $maxxp;
        protected $password;

        public function __construct()
        {
            $this->exp = new Rank($this->exp);
            return $this;
        }

        public function connect(PDO $db)
        {
            $this->db = $db;
            return $this;
        }

        public function getUserObject()
        {
            $getUserCount = $this->db->prepare("select count(*) from users where id = ?");
            $getUserCount->execute([$this->id]);
            if ($getUserCount->fetchColumn() == 1) {
                $getUser = $this->db->prepare("select * from users where id = ?");
                $getUser->execute([$this->id]);
                return $getUser->fetchObject(__CLASS__);
            }
        }

        public function setUserID($id)
        {
            $this->id = $id;
            return $this;
        }

        public function getFamily()
        {
            return (is_null($this->family)) ? "<i>Ingen</i>" : $this->family;
        }

        /**
         * @return mixed
         */
        public function getBank()
        {
            return $this->bank;
        }

        /**
         * @param mixed $bank
         */
        public function setBank($bank): void
        {
            $this->bank = $bank;
        }

        /**
         * @return mixed
         */
        public function getUsername()
        {
            return $this->user;
        }

        /**
         * @param mixed $username
         */
        public function setUsername($username): void
        {
            $this->user = $username;
        }

        /**
         * @return mixed
         */
        public function getSupport()
        {
            return $this->support;
        }

        /**
         * @param mixed $support
         */
        public function setSupport($support): void
        {
            $this->support = $support;
        }

        /**
         * @return mixed
         */
        public function getStatus()
        {
            return $this->status;
        }

        public function getStatusName()
        {
            switch ($this->getStatus()) {
                case 1:
                    return "<span class='stat1'>Administrator</span>";
                case 2:
                    return "<span class='stat2'>Moderator</span>";
                case 3:
                    return "<span class='stat3'>Forum-moderatr</span>";
                case 4:
                    return "<span class='stat5'>Spiller</span>";
                case 5:
                    return "<span class='statnpc'>NPC</span>";
                default:
                    return feil("FEIL");
            }
        }

        /**
         * @param mixed $status
         */
        public function setStatus($status): void
        {
            $this->status = $status;
        }

        /**
         * @return mixed
         */
        public function getPicmaker()
        {
            return $this->picmaker;
        }

        /**
         * @param mixed $picmaker
         */
        public function setPicmaker($picmaker): void
        {
            $this->picmaker = $picmaker;
        }

        /**
         * @return mixed
         */
        public function getPassword()
        {
            return $this->password;
        }

        /**
         * @param mixed $password
         */
        public function setPassword($password): void
        {
            $this->password = $password;
        }

        /**
         * @return mixed
         */
        public function getMail()
        {
            return $this->mail;
        }

        /**
         * @param mixed $mail
         */
        public function setMail($mail): void
        {
            $this->mail = $mail;
        }

        /**
         * @return mixed
         */
        public function getLastactive()
        {
            return $this->lastactive;
        }

        /**
         * @param mixed $lastactive
         */
        public function setLastactive($lastactive): void
        {
            $this->lastactive = $lastactive;
        }

        /**
         * @return mixed
         */
        public function getIp()
        {
            return $this->ip;
        }

        /**
         * @param mixed $ip
         */
        public function setIp($ip): void
        {
            $this->ip = $ip;
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @param mixed $id
         */
        public function setId($id): void
        {
            $this->id = $id;
        }

        /**
         * @return mixed
         */
        public function getHostname()
        {
            return $this->hostname;
        }

        /**
         * @param mixed $hostname
         */
        public function setHostname($hostname): void
        {
            $this->hostname = $hostname;
        }

        /**
         * @return mixed
         */
        public function getHealth()
        {
            return $this->health;
        }

        /**
         * @param mixed $health
         */
        public function setHealth($health): void
        {
            $this->health = $health;
        }

        /**
         * @return mixed
         */
        public function getHand()
        {
            return $this->hand;
        }

        /**
         * @param mixed $hand
         */
        public function setHand($hand): void
        {
            $this->hand = $hand;
        }

        /**
         * @return mixed
         */
        public function getExp()
        {
            return $this->exp;
        }

        /**
         * @param mixed $exp
         */
        public function setExp($exp): void
        {
            $this->exp = $exp;
        }

        /**
         * @return mixed
         */
        public function getCity()
        {
            return $this->city;
        }

        /**
         * @param mixed $city
         */
        public function setCity($city): void
        {
            $this->city = $city;
        }

        public function setRank($xp)
        {
        }

        public function lastDate()
        {
            return date("H:m:s d.m.Y", $this->getLastactive());
        }

        public function regDate()
        {
            return date("H:m:s d.m.Y", $this->regstamp);
        }

        public function handformat()
        {
            return number_format($this->getHand(), null, null, " ") . "kr";
        }

    }
}
