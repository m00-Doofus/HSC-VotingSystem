<?php
/*
 * @category Lib
 * @package Test Suit
 * @copyright 2011, 2012 Dmitry Sheiko (http://dsheiko.com)
 * @license GNU
 */
class Class_VoteOpener {
  private $_db;

  public function  __construct(Lib_Db $db) {
    $this->_db = $db;
  }
  /* @param int $RoomID
   * @return int
   */
  public function fetchVoteOpenByRoomID($RoomID) {
    return $this->_db->fetch("SELECT count(*) as count"
       . " FROM VoteOpener WHERE RoomID = %d"
       , $RoomID)->count;
  }
  /* @param int $RoomID
   * @param int $State   # '1' for 'open'; '0' for 'close'
   */
  public function OpenClose($RoomID, $State) {
    if ($State=="1") {
      $this->_db->update("INSERT INTO "
         . " VoteOpener (`RoomID`, `State`) VALUES ('%d', '1')"
         , $RoomID);
    } else {
      $this->_db->update("DELETE FROM "
         . " VoteOpener WHERE RoomID = %d"
         , $RoomID);
    }
  }
}