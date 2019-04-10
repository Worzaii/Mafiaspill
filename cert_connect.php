<?php
$connection = mysqli_init();
$path = "/var/www/mafia.werzaire.net/pemfiles";
$connection->ssl_set("$path/client-key.pem", "$path/client-cert.pem", "$path/ca.pem", null, null);
$connection->connect("127.0.0.1", "mafia", "mafia", "mafia");
if ($connection->connect_error != null) {
    die('Could not connect. ' . $connection->connect_error . ' Code: ' . $connection->connect_errno);
} else {
    echo 'Ready to ask. Trying to fetch user table with some data.';
}
$query = $connection->prepare("SELECT * FROM `users` where status = ? ORDER BY `id`");
$query->bind_param("i", $_GET['id']);
$query->execute();
$results = $query->get_result();
if ($results->num_rows >= 1) {
    echo '<p>Users:</p>';
    while ($d = $results->fetch_object()) {
        echo '<p>' . $d->user . '</p>';
    }
}