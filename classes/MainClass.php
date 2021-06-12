<?php

/**
 * Class MainClass
 * Structure for all game classes with interitance
 */
abstract class MainClass
{
    protected string $out = '';
    protected User $user;
    protected PDO $database;
    protected string $title;

    /**
     * MainClass constructor.
     * <p>Defaults to __construct()->execute()->loadpage()</p>
     * @param User $user
     * @param PDO $database
     * @param string $title
     */
    public function __construct(User $user, PDO $database, string $title = 'ReplaceMe')
    {
        $this->user = $user;
        $this->database = $database;
        $this->title = $title;
        $this->execute();
    }

    /**
     * Runs class function loadPage().
     */
    protected function execute()
    {
        $this->loadPage();
    }

    /**
     * Writes out the page to HTML.
     */
    protected function loadPage()
    {
        startpage($this->title);
        echo '<h1>' . $this->title . '</h1>';
        echo $this->out;
        endpage();
    }

    /**
     * This function serves as an optional limiter that might be extented at some point
     * @param int $jail
     * @param int $bunker
     * @param array $accesstable
     * @return string|false
     */
    protected function canUseFunction(int $jail = 0, int $bunker = 0, array $accesstable = ['ALL']): string|false
    {
        $write = "";
        if ($jail === 1) {
            $fe = fengsel(true);
            if ($fe !== false) {
                $write .= feil(
                        'Du er i fengsel, gjenstående tid: <span id="fengsel">' . $fe . '</span>
            <br>Du er ute kl. ' . date('H:i:s d.m.Y', (time() + $fe))
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
            teller($kltid, 'bunker', false, 'ned');
            </script>
ENDHTML;
            }
        }
        if (!in_array('ALL', $accesstable, true)) {
            $accesses = ['ADM', 'MOD', 'FMOD', 'PIC', 'User', 'ALL'];
            /**
             * Checking which accesses are passed as replacement
             */
            $stat = str_replace([1, 2, 3, 4, 5], ['ADM', 'MOD', 'FMOD', 'PIC', 'User'], $this->user->getStatus());
            if (!in_array($stat, $accesstable, true)) {
                $write .= noaccess(true);
                $write .= 'Status was: ' . $this->user->getStatus() . 'And stat is: ' . $stat;
            }
        }
        if (!empty($write)) {
            return $write;
        } else {
            return false;
        }
    }
}
