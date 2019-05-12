<table style="border: 2px solid #000;border-collapse: collapse" border="2px">
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
