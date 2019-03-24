<?php
include("core.php");
startpage("Oppdrag");
?>
<form method="post" action="">
<img src="imgs\oppdrag.png">
<h1>Oppdrag</h1>
    <table class="table">

        <thead>

            <tr>

                <th>Oppdrag 1</th>

            </tr>

        </thead>

        <tbody>

            <tr>

                <td>Oppdrag<br><br>Fullf&oslash;rt: !</td>

            </tr>

            <tr>

              <td style="text-align:center;"><input type="submit" name="submitteren" value="Fullf&oslash;r oppdraget!"></td>

            </tr>

        </tbody>

    </table>

  </form>
<?php
endpage();