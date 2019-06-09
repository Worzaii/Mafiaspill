<?php

namespace UserObject {

    class User
    {
        protected $password;
        private $id;
        private $username;
        private $mail;
        private $bank;
        private $hand;
        private $city;
        private $health;
        private $exp;
        private $status;
        private $support;
        private $picmaker;
        private $ip;
        private $hostname;
        private $lastactive;

        public function __construct()
        {
            return $this;
        }

        public function getUser($Identifier)
        {
            /* Insert some SQL query here somehow*/
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
            return $this->username;
        }

        /**
         * @param mixed $username
         */
        public function setUsername($username): void
        {
            $this->username = $username;
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

    }
}