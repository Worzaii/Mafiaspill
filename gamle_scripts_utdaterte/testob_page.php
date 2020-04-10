<?php
include "core2.php";
$content = "<h1>Hello world</h1><p class='info'>Have you seen the world lately? Lorem ipsum?</p>";
$page_title = "test";
function replace_content($buffer)
{
    global $obj;
    return str_replace(
        [
            "#ONLINE_USERS#",
            "#CHATS_NUMBER#",
            "##CONTENT##",
            "#USERNAME#",
            "#USER_ID#",
            "#USER_IMAGE#",
            "#USER_RANK#",
            "#USER_HAND#",
            "#USER_POINTS#",
            "#USER_CITY#",
            "#USER_HP#",
            "#USER_FAMILY#",
            "#USER_BULLETS#",
            "#USER_WEAPON#",
            "#PAGE_TITLE#",
            "#USER_INBOX#",
            "#CHAT_MESSAGES_HEADER#",
            "#WAIT_CRIME#",
            "#WAIT_CAR#",
            "#WAIT_ROB#",
            "#WAIT_JAIL#",
            "#WAIT_AIR#",
            "#WAIT_KILL#",
            "#WAIT_BUNKER_USER#"
        ],
        [
            $GLOBALS["online"],
            $GLOBALS["chats"],
            $GLOBALS["content"],
            $obj->user,
            $obj->id,
            $obj->image,
            rank($obj->exp)[1],
            number_format($obj->hand),
            $obj->points,
            $obj->city,
            $obj->health,
            $obj->family,
            $obj->bullets,
            $obj->weapon,
            $GLOBALS["page_title"],
            "(".$GLOBALS["messages"].")",
            $GLOBALS["chatmessages"]
        ],
        $buffer
    );
}

ob_start("replace_content");
$temp = file_get_contents("./templates/logged_in_user.html");
echo $temp;
ob_flush();
