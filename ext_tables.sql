#
# Table structure for table 'tx_html5videoplayer_domain_model_video'
#
CREATE TABLE tx_html5videoplayer_domain_model_video (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l10n_parent int(11) DEFAULT '0' NOT NULL,
	l10n_diffsource mediumtext,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	starttime int(11) DEFAULT '0' NOT NULL,
	endtime int(11) DEFAULT '0' NOT NULL,
	fe_group tinytext,
	title tinytext,
	description text,
	posterimage tinytext,
	mp4source tinytext,
	webmsource tinytext,
	oggsource tinytext,
	youtube tinytext,
	vimeo tinytext,
	height tinytext,
	width tinytext,
	downloadlinks tinyint(3) DEFAULT '0' NOT NULL,
	supportvideojs tinyint(3) DEFAULT '0' NOT NULL,
	preloadvideo tinytext,
	autoplayvideo tinyint(3) DEFAULT '0' NOT NULL,
	loopvideo tinyint(3) DEFAULT '0' NOT NULL,
	controlsvideo tinyint(3) DEFAULT '0' NOT NULL,
	video_starttime int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_html5videoplayer_videos text
);


#
# Table structure for table 'tx_html5videoplayer_video_content'
#
CREATE TABLE tx_html5videoplayer_video_content (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	content_uid int(11) DEFAULT '0' NOT NULL,
	video_uid int(11) DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid)
);


