<?php
define("BASEPATH", true);
include './system/config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Server and session info</title>
</head>
<body>
<h1>Server and session info</h1>
<h2>Server info: </h2>
<table style="border: 2px solid #000;border-collapse: collapse"
       border="2px">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_SERVER as $Key => $Value) {
        echo "<tr><td>$Key</td><td>$Value</td></tr>";
    }
    ?>
    </tbody>
</table>
<h2>Session info</h2>
<table style="border: 2px solid #000;border-collapse: collapse"
       border="2px">
    <thead>
    <tr>
        <th>Key</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($_SESSION as $Key => $Value) {
        if (is_array($Value)) {
            echo "<tr><td>$Key</td><td>" . var_export($Value, true) . "</td></tr>";
        } else {
            echo "<tr><td>$Key</td><td>$Value</td></tr>";
        }
    }
    ?>
    </tbody>
</table>
</body>
</html>

