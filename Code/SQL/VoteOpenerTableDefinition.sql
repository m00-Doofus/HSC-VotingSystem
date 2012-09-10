CREATE TABLE IF NOT EXISTS `VoteOpener` (
  `RoomID` int unsigned NOT NULL,
  `TimeStamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `State` tinyint unsigned NOT NULL,
  CONSTRAINT pk_RoomVote PRIMARY KEY (RoomID,TimeStamp)
  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Vote Open Lookup Table';