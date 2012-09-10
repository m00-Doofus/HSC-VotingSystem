<?php
/*
 * @category Sample
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */
define("BASE_PATH", dirname(__FILE__) );
# define("APP_PATH", realpath(BASE_PATH . "/../../"));
define("APP_PATH", realpath(BASE_PATH . "/../"));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Lib/Class_VoteOpener.php";;
$VoteOpener = new Class_VoteOpener(new Lib_Db(APP_PATH . "/App/config.php"));

set_time_limit (600);
date_default_timezone_set('America/New_York');

define("IDLE_TIME", 400000); # In microseconds (usleep)
                             # 0.4 seconds idle
                             # Longer means more variance between voters
                             # starting; shorter means more busy waiting

define("MAX_REQUEST_TIME", 30); # In seconds
                                # Time out after MAX_REQUEST_TIME.  If the
                                # client still wants data, they'll re-request.
                              

$RoomID = (int)$_REQUEST["RoomID"];
$ClientThinksVoteIsOpen = (int)$_REQUEST["ClientThinksVoteIsOpen"];
$TimeoutInMicro = MAX_REQUEST_TIME * 1000000;
$LoopCounter = floor( $TimeoutInMicro / IDLE_TIME );

do {
    usleep(IDLE_TIME);
    $UpdatedVoteIsOpen = $VoteOpener->fetchVoteOpenByRoomID($RoomID);
    $LoopCounter -= 1;
} while (($UpdatedVoteIsOpen == $ClientThinksVoteIsOpen) && ($LoopCounter >= 0));

header("HTTP/1.0 200");
printf ('%s({"time" : "%s", "UpdatedVoteIsOpen" : "%d"});'
    , $_REQUEST["callback"], date('d/m H:i:s'), $UpdatedVoteIsOpen);
// Clean up memory and stuff like that.
flush();
gc_collect_cycles();
?>