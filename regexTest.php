<?php
session_start();
if(isset($_POST['sub'])){
    print_r(preg_match("/^[\w-_]+[a-zA-Z]+[\w-_ ]*$/", $_POST['reg']));
    print_r(preg_match("/^[\w-_]+[a-z]+[\w-_ ]*$/", $_POST['re2']));
    print_r(preg_match("/^[\w-_]+[A-Z]+[\w-_ ]*$/", $_POST['re3']));
    print_r(preg_match("/^[a-zA-Z]*$/", $_POST['re4']));
    print_r(preg_match("/^[1-9]*$/", $_POST['re5']));
    print_r(preg_match("/^[\w-_a-zA-Z0-9 ]{1,20}*$/", $_POST['re6']));
}
?>
<form method="post">
    <input type="text" name="reg" placeholder="[\w-_]+[a-zA-Z]+[\w-_ ]">
    <input type="text" name="re2" placeholder="[\w-_]+[a-z]+[\w-_ ]">
    <input type="text" name="re3" placeholder="[\w-_]+[A-Z]+[\w-_ ]">
    <input type="text" name="re4" placeholder="[a-zA-Z]">
    <input type="text" name="re5" placeholder="[1-9]">
    <input type="text" name="re6" placeholder="[\w-_a-zA-Z0-9 ]">
    <br><input type="submit" name="sub" value="Sjekk!">
</form>
