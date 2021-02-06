<?php


namespace UserObject;

class Jail extends \mainclass
{
    public function __construct(User $user, PDO $database, string $title = "")
    {

    }

    protected function execute()
    {
        if ($write = canUseFunction(1, 1)) {
            $this->out .= $write;
        } else {
            $this->readyJail();
        }
        $this->out .= $this->listJailed();
        $this->loadPage();
    }

    private function readyJail()
    {
        $ready = $this->database->prepare("select count(*) from jail where uid = ? and breaker is null and timeleft > unix_timestamp() limit 1");
        $ready->execute([$this->user->getId()]);
        if ($ready->fetchColumn() == 1) {
            $this->isJailed();
        } else {
            $this->canBreakBuy();
        }
    }

    private function isJailed()
    {

    }

    private function canBreakBuy()
    {

    }

    private function listJailed()
    {
        $jailed = $this->database->prepare("select * from jail where breaker is null and timeleft > unix_timestamp()");

    }

    protected function loadPage()
    {
        startpage($this->title);
        echo "<h1>" . $this->title . "</h1>";
        echo <<<END
<img src="images/headers/fengsel.png">
END;
        echo $this->out;
        endpage();
    }
}
