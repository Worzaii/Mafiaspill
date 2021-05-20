<?php

abstract class MainClass
{
    protected string $out = "";
    protected User $user;
    protected PDO $database;
    protected string $title;

    public function __construct(User $user, PDO $database, string $title = "ReplaceMe")
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

    protected function canUseFunction(int $jail, int $bunker, array $accesstable): string|false
    {
        $write = "";
        if ($jail === 1) {
            $fe = fengsel(true);
            if ($fe !== false) {
                $write .= feil(
                        'Du er i fengsel, gjenstående tid: <span id="fengsel">' . $fe . '</span>
            <br>Du er ute kl. ' . date("H:i:s d.m.Y", (time() + $fe))
                    ) .
                    '<script type="text/javascript">teller(' . $fe . ', "fengsel", false, \'ned\');</script>';
            }
        }
        if ($bunker === 1) {
            $bu = bunker(true);
            if ($bu !== false) {
                $kl = date("H:i:s d.m.Y", $bu);
                $kltid = $bu - time();
                $write .= <<<ENDHTML
            <p class="feil">Du er i bunker, gjenstående tid:
            <span id="bunker">$bu</span><br>Du er ute kl. $kl</p>
            <script type="text/javascript">
            teller($kltid, "bunker", false, 'ned');
            </script>
ENDHTML;
            }
        }
        if (!empty($write)) {
            return $write;
        } else {
            return false;
        }
    }
}
