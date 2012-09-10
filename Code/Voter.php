<?php
/*
 * @category Sample
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */
define("BASE_PATH", dirname(__FILE__) );
# define("APP_PATH", realpath(BASE_PATH . "/../../"));
define("APP_PATH", realpath(BASE_PATH));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Lib/Class_VoteOpener.php";;
$VoteOpener = new Class_VoteOpener(new Lib_Db(APP_PATH . "/App/config.php"));

$RoomID = 1; 
$VoteIsOpen = (int)$VoteOpener->fetchVoteOpenByRoomID($RoomID);
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Voter Interface</title>
  <script type="text/javascript" src="App/Lib/jquery-1.8.0.min.js"></script>
</head>
<body>
  <p>Room: <?php echo $RoomID; ?></p>
  <?php
    if ($VoteIsOpen=="1") {
      $ButtonsDisabled="";
      }
    else {
      $ButtonsDisabled='disabled="disabled"';
      }
  ?>
  <input type="button" id="BtnYea" <?php echo $ButtonsDisabled; ?> value="Yea" />
  <input type="button" id="BtnNay" <?php echo $ButtonsDisabled; ?> value="Nay" />
  <input type="hidden" id="ClientThinksVoteIsOpen" value="<?php echo $VoteIsOpen; ?>" />
<?php // To Delete later..... ?>
  <p>Last Results Received: <input id="time"/></p>
<?php // To Delete later..... ?>

<script type="text/javascript">

(function( $ ) {

$.LongPollingVoter = (function() {
    var _BtnYea = $('#BtnYea'),
        _BtnNay = $('#BtnNay'),
        _stateNode = $('#ClientThinksVoteIsOpen'),
        _timeNode = $('#time'),
        RoomID = <?php echo $RoomID; ?>;
    return {
        onMessage : function(data) {
            switch (data.UpdatedVoteIsOpen) {
              case "1":
                _BtnYea.prop("disabled", false);
                _BtnNay.prop("disabled", false);
                break;
              default:
                _BtnYea.prop("disabled", true);
                _BtnNay.prop("disabled", true);
              }
            _stateNode.val(data.UpdatedVoteIsOpen);
            _timeNode.val(data.time);
            setTimeout($.LongPollingVoter.send, 40);
        },
        send : function() {
            // alert("send function called -- RoomID:"+RoomID+" ClientThinksVoteIsOpen:"+_stateNode.val());           
            $.ajax({
                    'url': 'App/VoterServer.php',
                    'type': 'POST',
                    'dataType': 'jsonp',
                    'jsonpCallback': '$.LongPollingVoter.onMessage',
                    'data': 'RoomID=' + RoomID + '&ClientThinksVoteIsOpen='
                        + _stateNode.val()
            });
        }
    }
}());

// Document is ready
$(document).bind('ready.app', function() {
   setTimeout($.LongPollingVoter.send, 40); 
});

})( jQuery );


</script>
</body>
</html>