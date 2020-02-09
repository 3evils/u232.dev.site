ALTER TABLE `torrents` ADD `Snatched` int(10) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `torrents` ADD `balance` bigint(20) NOT NULL DEFAULT '0';

CREATE TABLE `users_freeleeches` (
  `UserID` int(10) NOT NULL,
  `TorrentID` int(10) NOT NULL,
  `Time` datetime NOT NULL,
  `Expired` tinyint(1) NOT NULL DEFAULT '0',
  `Downloaded` bigint(20) NOT NULL DEFAULT '0',
  `Uses` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`UserID`,`TorrentID`),
  KEY `Time` (`Time`),
  KEY `Expired_Time` (`Expired`,`Time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `xbt_files_users` ADD `ip` varchar(39) COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `xbt_files_users` ADD `corrupt` bigint(20) NOT NULL DEFAULT '0';
ALTER TABLE `xbt_files_users` ADD `useragent` varchar(51) COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
CREATE TABLE `xbt_snatched` (
  `uid` int(11) NOT NULL DEFAULT '0',
  `tstamp` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `IP` varchar(15) NOT NULL,
  KEY `fid` (`fid`),
  KEY `tstamp` (`tstamp`),
  KEY `uid_tstamp` (`uid`,`tstamp`)
) ENGINE=InnoDB CHARSET utf8;