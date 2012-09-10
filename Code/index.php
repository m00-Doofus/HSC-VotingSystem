<?php
/*
 * @category Sample
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */
define("BASE_PATH", dirname(__FILE__) );
# define("APP_PATH", realpath(BASE_PATH . "/../"));
define("APP_PATH", realpath(BASE_PATH));
require_once APP_PATH . "/App/Lib/Db.php";
require_once APP_PATH . "/App/Lib/Class_VoteOpener.php";;
$VoteOpener = new Class_VoteOpener(new Lib_Db(APP_PATH . "/App/config.php"));

# 'define'd in case you want to use a different page per room, etc
define('ROOM_ID', 1);
$RoomID = ROOM_ID;

# Get Current state of Room (just in case....)
$VoteIsOpen = (int)$VoteOpener->fetchVoteOpenByRoomID($RoomID);

if ($VoteIsOpen=="1") {
  $OpenButtonDisabled='disabled="disabled"';
  $CloseButtonDisabled="";
  }
else {
  $OpenButtonDisabled="";
  $CloseButtonDisabled='disabled="disabled"';
  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Voting Demo</title>
  <script type="text/javascript" src="App/Lib/jquery-1.8.0.min.js"></script>
  <script language="javascript">
    <!--//
    // Note: 'windowname' should either be left blank, or one of the funny
    // named windows should be used (ie '_blank', '_parent', etc)
    // (http://stackoverflow.com/questions/710756/ie8-var-w-window-open-message-invalid-argument)
    function VoterPopup(url,windowname,w,h,x,y){
      window.open(url,windowname,"resizable=no,toolbar=no,scrollbars=no,menubar=no,status=no,directories=no,width="+w+",height="+h+",left="+x+",top="+y+"");
    }
    //-->
  </script>
</head>
<body>
  <p>Examples</p>
    <dl>
      <dt><a href="javascript:VoterPopup('Voter.php','','300','300','10','300')">
              Voting Screen (new window)
      </a></dt>
    </dl>
  <fieldset>
    <legend>Invokation tool for Long-polling Voting example</legend>
    <p>SubmissionResultStatus: <var id="status">Status is currently
      <?php
        if ($VoteIsOpen=="1") {
          echo "Open";
          }
        else {
          echo "Closed";
          } 
      ?></var></p>
    <button name="OpenVote" <?php echo $OpenButtonDisabled; ?>>Open a new Vote</button>
    <button name="CloseVote" <?php echo $CloseButtonDisabled; ?>>Close Vote</button>
  </fieldset>

<script type="text/javascript">

<?php
/*
function DisableActiveButton() {
var filled = 0
var x = document.form1.title.value;
x = x.replace(/^\s+/,""); // strip leading spaces
if (x.length > 0) {filled ++}

var y = document.form1.description.value;
y = y.replace(/^s+/,""); // strip leading spaces
if (y.length > 0) {filled ++}

var z = document.form1.theName.value;
z = z.replace(/^s+/,""); // strip leading spaces
if (z.length > 0) {filled ++}

if (filled == 3) {
document.getElementById("submitForm").disabled = false;
}
*/
?>

(function( $ ) {

var RoomID = <?= $RoomID ?>;

App = (function() {
  var _OpenBtn = $('button[name=OpenVote]'),
      _CloseBtn = $('button[name=CloseVote]'),
      _status = $('#status'),
      _handler = {
        onOpen : function(e) {
          e.preventDefault();
          // jQuery.post( url [, data] [, success(data, textStatus, jqXHR)]
          //    [, dataType] )
          $.post('App/SubmissionHandler.php', 
            {'VoteOpenerAction': 'open', 'RoomID': RoomID },
            function(returned){
              _status.html(returned);
              _OpenBtn.attr("disabled", "disabled");
              _CloseBtn.removeAttr("disabled");
              }
            );
          },
        onClose : function(e) {
          e.preventDefault();
          $.post('App/SubmissionHandler.php', 
            {'VoteOpenerAction': 'close', 'RoomID': RoomID },
            function(returned){
              _status.html(returned);
              _OpenBtn.removeAttr("disabled");
              _CloseBtn.attr("disabled", "disabled");
              }
            );
          }
        };
  return {
      init : function(data) {
        _OpenBtn.bind('click', this,  _handler.onOpen);
        _CloseBtn.bind('click', this,  _handler.onClose);
        }
      }
}());

// Document is ready
$(document).bind('ready.app', App.init);

})( jQuery );

</script>
</body>
</html>