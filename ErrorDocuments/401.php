<html>
<head>
    <title>401 - Ingen tilgang</title>
</head>
<body>
<h1>Du har ikke tilgang til å åpne denne siden.</h1>
<p>Hvis du mener at dette er feil, send en e-post ved å <a
        href="mailto:werzairenet@getmail.no?Subject=<?= (rawurlencode(
            "\u{00D8}nsker tilgang"
        )) ?>&Body=<?= (rawurlencode("IP-adresse: " . $_SERVER['REMOTE_ADDR'] . "\nLegg til kommentar her:\n\n")) ?>">trykke
        her</a> og fylle inn følgende informasjon: </p>
<p>IP-adresse: <?= $_SERVER['REMOTE_ADDR']; ?></p>
</body>
</html>
