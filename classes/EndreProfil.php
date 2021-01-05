<?php

namespace UserObject\EndreProfil;

use PDO;
use UserObject\User;

class EndreProfil
{
    public User $user;
    public object $database;
    public string $out = "";
    
    public function __construct(User $user, PDO $database)
    {
        $this->user = $user;
        $this->database = $database;
        $this->execute();
    }
    
    private function execute()
    {
        if ($write = canUseFunction(1, 1)) {
            $this->out .= $write;
        } else {
            $this->readyEndreProfil();
        }
        $this->loadPage();
    }
    
    public function readyEndreProfil()
    {
         if (isset($_POST['endre'])) {
            $this->tryEndreProfil($_POST['profilb'], $_POST['profilt']);
        }
    }
   
    public function tryEndreProfil($profilb, $profilt)
    {
        if ($profilb == NULL) {
            $this->out .= feil("Du må ha et profilbilde!");
        } else {
            $this->doEndreProfil($profilb, $profilt);
        }
    }
    
    public function doEndreProfil($profilb, $profilt)
    {
        $endreprofilen = $this->database->prepare("UPDATE `users` SET `image` = ?, `profile` = ? WHERE `id` = ?");
        $endreprofilen->execute([$profilb, $profilt, $this->user->getId()]);
        if ($endreprofilen->rowCount() == 1) {
            $this->out .= lykket('Profilen din har blitt endret!');
        } else {
            $this->out .= feil('Du kunne ikke endre profilen på grunn av en feil i enten spørring eller i databasen, ta kontakt med Ledelsen!');
        }
    }
    
    public
    function loadPage()
    {
        startpage("Rediger profil");
                echo $this->out;
        ?>
        <form method="post">
     <table class="table">
     	<tr><th colspan="2">Rediger profil</th></tr>
     	<tr><td>Profilbilde:</td><td><input type="text" value="<?php echo $this->user->image; ?>" name="profilb"></td></tr>
     	<tr><td colspan="2">Profil tekst</td></tr>
     	<tr><td colspan="2"><textarea name="profilt" style="margin: 0px; height: 300px; background-color: #aaa;"><?php echo $this->user->profile; ?></textarea></td></tr>
     	<tr><td colspan="2"><input type="submit" name="endre"></td></tr>
     </table>
 </form>
        <?php

        endpage();
    }
    
}