<?php
/*
 * This will handle all 'POST's from front ends
 */

define("BASE_PATH", dirname(__FILE__) );
define("APP_PATH", realpath(BASE_PATH . "/../"));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Lib/Class_VoteOpener.php";;
$VoteOpener = new Class_VoteOpener(new Lib_Db(APP_PATH . "/App/config.php"));

if ($_SERVER['REQUEST_METHOD']!='POST') {
  exit("You should only be posting data to me...");
  }
if (isset($_POST['VoteOpenerAction']) && isset($_POST['RoomID'])) {
  switch ($_POST['VoteOpenerAction']) {
    # 'OpenClose' function takes two arguments:
    # 'RoomID' and 'State' (1 for open; 0 for closed) 
    case "open":
        $VoteOpener->OpenClose($_POST['RoomID'], 1);
        break;
    default:   # 'close' all others (including the proper 'close')
        $VoteOpener->OpenClose($_POST['RoomID'], 0);
        break;
    }
  $VoteIsOpen = (int)$VoteOpener->fetchVoteOpenByRoomID($_POST['RoomID']);
    if ($VoteIsOpen=="1") {
      echo "Voting is Open!";
      }
    else {
      echo "Voting is Closed!";
      }

  }
?>