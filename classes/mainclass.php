<?php

use \UserObject\User;

abstract class mainclass
{
    protected string $out = "";
    protected User $user;
    protected PDO $database;
    protected string $title;

    public function __construct(User $user, PDO $database, string $title = "")
    {
        $this->user = $user;
        $this->database = $database;
        $this->title = $title;
        $this->execute();
    }

    protected function execute()
    {
        $this->loadPage();
    }

    protected function loadPage()
    {
        startpage($this->title);
        echo "<h1>" . $this->title . "</h1>";
        echo $this->out;
        endpage();
    }
}
