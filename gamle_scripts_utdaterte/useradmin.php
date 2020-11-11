<?php
/**
 * Todo: Program a usable user editor, for creating, deleting and editing at first.
 */
include "core.php";
if (r1()) {
    startpage("Brukeradministrasjon");
    echo <<<HTML
<h1>Brukeradministrasjon</h1>
<p class="info">Her vil det etter hvert komme funksjonalitet som vil tillatte oppretting, redigering og sletting av brukerkontoer. Vurderer også å lage det med AJAX for letthetens skyld.</p>

HTML;


} else {
    startpage("Ingen tilgang");
    noaccess();
}
endpage();
