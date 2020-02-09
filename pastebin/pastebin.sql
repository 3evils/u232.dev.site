--
-- Table structure for table `pastebin`
--

CREATE TABLE IF NOT EXISTS `pastebin` (
  `pid` int(11) NOT NULL auto_increment,
  `poster` varchar(16) default NULL,
  `posted` datetime default NULL,
  `code` text,
  `parent_pid` int(11) default '0',
  `format` varchar(16) default NULL,
  `codefmt` mediumtext,
  `codecss` text,
  `domain` varchar(255) default '',
  `token` varchar(32) default NULL,
  `expires` datetime default NULL,
  `expiry_flag` enum('d','m','f') NOT NULL default 'm',
  PRIMARY KEY  (`pid`),
  KEY `domain` (`domain`),
  KEY `expires` (`expires`),
  KEY `parent_pid` (`parent_pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `recent`
--

CREATE TABLE IF NOT EXISTS `recent` (
  `domain` varchar(255) NOT NULL default '',
  `pid` int(11) NOT NULL,
  `seq_no` int(11) NOT NULL,
  PRIMARY KEY  (`domain`,`seq_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;