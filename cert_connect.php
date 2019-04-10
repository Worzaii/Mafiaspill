<?php
$connection = mysqli_init();
$path = "/var/www/mafia.werzaire.net/pemfiles";
$connection->ssl_set("$path/client-key.pem", "$path/client-cert.pem", "$path/ca.pem", NULL, NULL);
$connection->connect("127.0.0.1", "mafia", "mafia","mafia");
if($connection->connect_error != NULL){
    echo 'Could not connect. '.$connection->connect_error. ' Code: '.$connection->connect_errno;
}
else{
    echo 'Ready to ask';
}
