<?php


namespace UserObject;

class Profil extends \mainclass
{
    /* Initializing out variable as it doesn't get initialized from extending */
    protected string $out = "";

    private function checkProfile($id)
    {
        $u = $this->database->prepare("select count(*) from users where id = ?");
        $u->execute([$id]);
        if ($u->fetchColumn() === 1) {
            $this->getProfile($id);
        } else {
            $this->out .= warning(
                "Det var ikke funnet noen bruker med id: " . htmlentities(
                    $id
                ) . "! Bruk søkefunksjonen <a href=\"finnspiller.php\"> Finn spiller </a> for å finne spillere"
            );
        }
    }

    protected function execute()
    {
        //parent::execute(); // TODO: Change the autogenerated stub
        $this->checkProfile($_GET['id']);
        $this->loadPage();
    }

    private function getProfile($id)
    {
        $u = $this->database->prepare(
            "select user,exp,image,profile,family,hand,status,support,lastactive,regstamp,picmaker from users where id = ?"
        );
        $u->execute([$id]);
        $profile = $u->fetchObject(User::class);
        $profiletext = new \BBcodes(!is_null($profile->profile) ? $profile->profile : "");
        $this->out .= <<<ENDHTML
<table style="width:310px;margin - top: 60px;" class="table ekstra">
<tr>
<td style="text - align:center;" colspan="2" class="img"><img alt='Brukeren sitt profilbilde' id='profileuserimage' src="{$profile->image}" style="width:250px;height:250px;text - align:center"></td>
</tr>
<tr><th colspan="2">Om</th></tr>
<tr>
<td>Nick:</td><td><a href="innboks.php?page=ny&user={$profile->user}">{$profile->user}</a></td>
</tr>
<tr>
<td>Sist pålogget:</td><td>{$profile->lastDate()}</td>
</tr>
<tr>
<td>Dato registrert:</td><td>{$profile->regDate()}</td>
</tr>
<tr>
<td>Penger ute:</td><td>{$profile->handformat()}</td>
</tr>
<tr>
<td>Meldinger sendt:</td><td>XXXX</td>
</tr>
<tr>
<td>Rank:</td><td>{$profile->exp->getRank()}</td>
</tr>
<tr>
<td>Familie:</td><td>{$profile->getFamily()}</td>
</tr>
<tr>
<td>Status:</td><td>{$profile->getStatusName()}</td>
</tr>
</table>
<br>

<div class="profiltekst">
{$profiletext->applyAllBBcodes()}
</div>
ENDHTML;
    }

    /* Overwriting for special design */
    protected function loadPage()
    {
        startpage($this->title . " - ");
        echo "<h1>" . $this->title . " - </h1><br>";
        echo $this->out;
        endpage();
    }
}
