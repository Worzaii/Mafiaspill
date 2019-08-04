<?php
$melding = $_POST["tekst"];
?>
    <script>
      //Sp&oslash;r om tilatelse for og vise skrivebords varsler
      document.addEventListener('DOMContentLoaded', function() {
        if (Notification.permission !== 'granted')
          Notification.requestPermission();
      });

      function notifyMe() {
        if (!Notification) {
          alert('Desktop notifications not available in your browser. Try Chromium.');
          return;
        }

        if (Notification.permission !== 'granted')
          Notification.requestPermission();
        else {
          var notification = new Notification('WestMafia varsling system v1', {
            icon: 'http://mafiaspillet.no/imgupload/218844_big.jpg',
            body: 'Det funker som det skall!',
          });

          notification.onclick = function() {
            window.open('https://mafia.werzaire.net/');
          };

        }

      }

      function notifydead() {
        if (!Notification) {
          alert('Desktop notifications not available in your browser. Try Chromium.');
          return;
        }

        if (Notification.permission !== 'granted')
          Notification.requestPermission();
        else {
          var notification = new Notification('Du har blitt drept!', {
            icon: 'http://mafiaspillet.no/imgupload/218844_big.jpg',
            body: 'Din bruker har nettop blitt drept!',
          });

          notification.onclick = function() {
            window.open('http://westmafia.net/');
          };

        }

      }

      function notifypres() {
        if (!Notification) {
          alert('Desktop notifications not available in your browser. Try Chromium.');
          return;
        }

        if (Notification.permission !== 'granted')
          Notification.requestPermission();
        else {
          var notification = new Notification('Noen stjal penger fra deg!', {
            icon: 'http://mafiaspillet.no/imgupload/218844_big.jpg',
            body: 'Noen har nettop stjelt penger fra deg!',
          });

          notification.onclick = function() {
            window.open('http://westmafia.net/');
          };

        }

      }

      function notifykilled() {
        if (!Notification) {
          alert('Desktop notifications not available in your browser. Try Chromium.');
          return;
        }

        if (Notification.permission !== 'granted')
          Notification.requestPermission();
        else {

          var notification = new Notification('Melding fra ledelsen!', {
            icon: 'http://mafiaspillet.no/imgupload/218844_big.jpg',
            body: <?php echo json_encode($melding); ?>,
          });

          notification.onclick = function() {
            window.open('http://westmafia.net/');
          };

        }

      }
    </script>
<?php
if ($_GET["type"] == 2) {
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if (strpos($user_agent, 'Chrome') !== false) {
        echo "Google Chrome<br>";
    } else {
        echo "Please use google chrome for best effect of this!<br>";
    }
    ?>
    <button onclick="notifyMe()">Gi meg et varsel!(Test)</button>
    <button onclick="notifydead()">Gi meg et varsel! (Drept)</button>
    <button onclick="notifypres()">Gi meg et varsel! (Stjel)</button><br><br><h1>Send varsel melding fra ledelsen</h1>
    <br>
    <form method="post" name="ledmes">
        <textarea id="ledmes" style="width:100%;height:600px" placeholder="Skriv meldingen her!"
                  name="tekst"></textarea><br>
        <input type="submit" value="Send meldingen!">
    </form>
    <?php
} else {
    ?>


    <?php
}
?>
<?php
if ($melding != null) {
    echo "Teksten som ble sendt:<br> $melding ";
    echo '<script>'
    , 'notifykilled();'
    , '</script>';
    echo "<br>";
}
?>