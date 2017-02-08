CREATE TABLE `categories` (
  `catid` int(5) NOT NULL auto_increment,
  `catname` varchar(50) NOT NULL,
  `catitems` int(5) default '0',
  `catdesc` text,
  `catlink` varchar(50) NOT NULL,
  `secid` int(3) NOT NULL default '1',
  `cattimestamp` int(10) default '0',
  `catlastedit` int(10) default '0',
  `cathit` int(10) default '0',
  `eval` tinyint(1) default '0',
  `caticon` varchar(150) default NULL,
  PRIMARY KEY  (`catid`),
  UNIQUE KEY `catlink` (`catlink`),
  FULLTEXT KEY `catname` (`catname`),
  FULLTEXT KEY `catdesc` (`catdesc`),
  FULLTEXT KEY `catlink_2` (`catlink`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COMMENT='This table contains our site categories';

CREATE TABLE `content` (
  `pageid` int(7) NOT NULL auto_increment,
  `catid` int(5) NOT NULL default '1',
  `secid` int(3) NOT NULL default '1',
  `pagelink` varchar(50) NOT NULL,
  `linktitle` varchar(75) NOT NULL,
  `pagetitle` varchar(150) NOT NULL,
  `pagedesc` text,
  `pagecontent` text,
  `contenttime` int(10) default '0',
  `contentlastedit` int(10) default '0',
  `contenthit` int(10) default '0',
  `evaldesc` tinyint(1) default '0',
  `evalcont` tinyint(1) default '0',
  `pageicon` varchar(150) default NULL,
  PRIMARY KEY  (`pageid`),
  UNIQUE KEY `pagelink` (`pagelink`,`pagetitle`),
  UNIQUE KEY `linktitle` (`linktitle`),
  FULLTEXT KEY `pagelink_2` (`pagelink`,`pagetitle`,`pagedesc`,`pagecontent`)
) ENGINE=MyISAM AUTO_INCREMENT=41 DEFAULT CHARSET=latin1 COMMENT='This table contains the main content of our pages.';

CREATE TABLE `downloads` (
  `id` int(50) NOT NULL auto_increment,
  `linkname` varchar(50) NOT NULL,
  `name` varchar(255) default NULL,
  `location1` varchar(255) NOT NULL,
  `location2` varchar(255) default NULL,
  `location3` varchar(255) default NULL,
  `dlhits` int(50) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `location1` (`location1`),
  UNIQUE KEY `linkname` (`linkname`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='A DCEvo table to keep track of mirrored downloads';

CREATE TABLE `extramenus` (
  `itemid` int(50) NOT NULL auto_increment,
  `pageid` text,
  `location` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `admintitle` varchar(255) NOT NULL,
  PRIMARY KEY  (`itemid`),
  FULLTEXT KEY `location` (`location`,`title`),
  FULLTEXT KEY `pageid` (`pageid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='This is an extra table for DC Evo''s download menu';

CREATE TABLE `idtable` (
  `id` varchar(100) NOT NULL,
  `idtype` varchar(4) NOT NULL default 'page',
  `idname` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`),
  FULLTEXT KEY `id_2` (`id`,`idname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This table makes sure we do not have duplicate links';

CREATE TABLE `news` (
  `newstime` int(10) NOT NULL default '0',
  `username` varchar(100) default NULL,
  `newsposter` varchar(100) default NULL,
  `newsemail` varchar(150) default NULL,
  `newssubject` varchar(150) NOT NULL,
  `newscontent` text NOT NULL,
  `newsextended` text,
  UNIQUE KEY `newstime` (`newstime`),
  KEY `username` (`username`),
  FULLTEXT KEY `newsposter` (`newsposter`,`newsemail`,`newssubject`,`newscontent`),
  FULLTEXT KEY `newsextended` (`newsextended`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This table contains our news posts.';

CREATE TABLE `polloptions` (
  `optionid` int(255) NOT NULL auto_increment,
  `pollid` int(10) NOT NULL,
  `option` varchar(255) NOT NULL,
  `votes` int(255) default '0',
  PRIMARY KEY  (`optionid`),
  FULLTEXT KEY `option` (`option`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1 COMMENT='This table contains the options for the various CAGCMS polls';

CREATE TABLE `polls` (
  `pollid` int(10) NOT NULL auto_increment,
  `pollname` varchar(100) NOT NULL,
  `pollquestion` varchar(255) NOT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime default NULL,
  `pollpages` text,
  PRIMARY KEY  (`pollid`),
  UNIQUE KEY `startdate` (`startdate`),
  FULLTEXT KEY `pollpages` (`pollpages`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COMMENT='This table contains any poll information for the CAGCMS site';

CREATE TABLE `pollvotes` (
  `voteid` int(100) NOT NULL auto_increment,
  `pollid` int(10) NOT NULL,
  `optionid` int(100) NOT NULL,
  `voterip` varchar(255) NOT NULL,
  PRIMARY KEY  (`voteid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='This table holds all of the votes that have been cast in var';

CREATE TABLE `screenshots` (
  `imgid` int(25) NOT NULL auto_increment,
  `pageid` text,
  `location_thumb` varchar(255) default NULL,
  `location` varchar(255) NOT NULL,
  `caption` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  PRIMARY KEY  (`imgid`),
  FULLTEXT KEY `location` (`location`),
  FULLTEXT KEY `pageid` (`pageid`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COMMENT='This is an extra table added for DC Evo for screenshots';

CREATE TABLE `sections` (
  `secid` int(3) NOT NULL auto_increment,
  `secname` varchar(50) NOT NULL,
  `secitems` int(5) default '0',
  `secdesc` text,
  `seclink` varchar(50) NOT NULL,
  `sectimestamp` int(10) default '0',
  `seclastedit` int(10) default '0',
  `sechit` int(10) default '0',
  `eval` tinyint(1) default '0',
  `secicon` varchar(150) default NULL,
  PRIMARY KEY  (`secid`),
  UNIQUE KEY `secname` (`secname`),
  FULLTEXT KEY `secdesc` (`secdesc`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='This table contains our site sections.';

CREATE TABLE `usergroups` (
  `groupid` int(5) NOT NULL auto_increment,
  `groupname` varchar(150) NOT NULL,
  `members` int(10) default '0',
  `permissions` varchar(30) default '0',
  PRIMARY KEY  (`groupid`),
  UNIQUE KEY `groupname` (`groupname`),
  FULLTEXT KEY `groupname_2` (`groupname`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COMMENT='This table contains information about our CAGCMS user groups';

CREATE TABLE `users` (
  `userid` int(10) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `useremail` varchar(150) NOT NULL,
  `userrealname` varchar(150) default NULL,
  `userlevel` varchar(50) default NULL,
  `userip` varchar(50) default NULL,
  `userreg` int(10) default '0',
  `userlogin` int(10) default '0',
  `userlastclicktime` int(10) default '0',
  `userlastclick` varchar(150) default NULL,
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `username` (`username`,`useremail`),
  UNIQUE KEY `userrealname` (`userrealname`),
  FULLTEXT KEY `username_2` (`username`,`password`,`useremail`,`userlevel`,`userip`,`userlastclick`),
  FULLTEXT KEY `userrealname_2` (`userrealname`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='This is the table of our users';

INSERT INTO `users` VALUES (1,'admin','ISMvKXpXpadDiUoOSoAfww','username@example.com','CAGCMS Administrator','65535|65535|65535|65535|65535|65535|65535','|75.104.147.167|75.105.250.25',0,1195398603,1195398603,NULL);