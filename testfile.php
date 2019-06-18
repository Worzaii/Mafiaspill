<?php
namespace MafiaGame;
spl_autoload_register(function ($class) {
    echo '<p>Trying to include class '.$class.'</p>';
    $file = file_exists("./classes/".$class.".php");
    echo ($file) ? "<p>File exists! Including...</p>" : "<p>File seems not to exist!</p>";
    include './classes/' . $class . '.php';
});
/*interface User
{

}*/
$user = new \UserObject\User();
var_dump($user);
