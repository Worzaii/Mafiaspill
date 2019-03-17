<?php

/*
 * 3d10-simple-blackjack-dealer.php
 * by Duane O'Brien - http://chaoticneutral.net
 * written for IBM DeveloperWorks
 */

/* First, we have the suits */

$suits = array (
    "Spades", "Hearts", "Clubs", "Diamonds"
);
/*
Spar, Hjerte, kløver, ruter
*/

/* Next, we declare the faces*/

$faces = array (
    "Two"=>2, "Three"=>3, "Four"=>4, "Five"=>5, "Six"=>6, "Seven"=>7, "Eight"=>8,
    "Nine"=>9, "Ten"=>10, "Jack"=>10, "Queen"=>10, "King"=>10, "Ace"=>11
);

function evaluateHand($hand) {
    global $faces;
    $value = 0;
    foreach ($hand as $card) {
        if ($value > 11 && $card['face'] == 'Ace') {
            // There's a bug here.  If you draw Ace-Five-Ace-Ten, it thinks you have 27.
            // Have a go at fixing that bug.  Email me if you have problems. - DPO
            $value = $value + 1;
        } else {
            $value = intval($value) + intval($faces[$card['face']]);
        }
    }
    return $value;
}

/* Now build the deck by combining the faces and suits. */

$deck = array();

foreach ($suits as $suit) {
    $keys = array_keys($faces);
    foreach ($keys as $face) {
        $deck[] = array('face'=>$face,'suit'=>$suit);
    }
}

/* Next, you can shuffle up the deck and pull a random card. */

shuffle($deck);

$hand = array();

if (empty($_POST)) {
    
    for ($i = 0; $i < 2; $i++) {
        $hand[] = array_shift($deck);
        $dealer[] = array_shift($deck);
    }

    $handstr = serialize($hand);
    $deckstr= serialize($deck);
    $dealerstr= serialize($dealer);
} else if ($_POST['submit'] == 'stay') {
    $dealer = unserialize($_POST['dealerstr']);
    $hand = unserialize($_POST['handstr']);
    $deck = unserialize($_POST['deckstr']);
    while(evaluateHand($dealer) < 17) {
        $dealer[] = array_shift($deck);
    }
    echo "Dealer hit " . evaluateHand($dealer) . "<br />\n";
    echo "You hit " . evaluateHand($hand) . "<br />\n";
    $handstr = $_POST['handstr'];
    $dealerstr = serialize($dealer);
    $deckstr= serialize($deck);
} else if ($_POST['submit'] == 'hit me') {
    $dealer = unserialize($_POST['dealerstr']);
    $hand = unserialize($_POST['handstr']);
    $deck = unserialize($_POST['deckstr']);
    $hand[] = array_shift($deck);
    $dealerstr = $_POST['dealerstr'];
    $handstr = serialize($hand);
    $deckstr= serialize($deck);}
    ?>
<form method='post'>
<input type='hidden' name='handstr' value = '<?php echo $handstr ?>' />
<input type='hidden' name='deckstr' value = '<?php echo $deckstr ?>' />
<input type='hidden' name='dealerstr' value = '<?php echo $dealerstr ?>' />
<?php

foreach ($hand as $index =>$card) {
    echo $card['face'] . ' of ' . $card['suit'] . "<br />";
}

?>
<p>You have : <?php echo evaluateHand($hand); ?></p>
<p>Dealer is showing the <?php echo $dealer[0]['face'] ?> of <?php echo $dealer[0]['suit'] ?></p> 
<input type='submit' name='submit' value='hit me' />
<input type='submit' name='submit' value='stay' />
</form>
<a href='bjtest.php'>Try Again</a>