<?php
 /* 
  * Handle AJAX lookups here
  */

define("BASE_PATH", dirname(__FILE__) );
# define("APP_PATH", realpath(BASE_PATH . "/../../"));
define("APP_PATH", realpath(BASE_PATH . "/../"));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Lib/Class_VoteOpener.php";;
$VoteOpener = new Class_VoteOpener(new Lib_Db(APP_PATH . "/App/config.php"));

$ElementToLookup = $_REQUEST["ElementToLookup"];

if ($ElementToLookup=="VoteActive" && isset($_REQUEST["RoomID"])) {
  $RoomID = $_REQUEST["RoomID"];
  # I know it's not XML, so not _REALLY_ AJA_X_, but....
  echo $VoteOpener->fetchVoteOpenByRoomID($RoomID);
  }

?>