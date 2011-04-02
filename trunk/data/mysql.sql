CREATE TABLE <user> (uid INT(4) AUTO_INCREMENT NOT NULL PRIMARY KEY,username VARCHAR(30) NOT NULL,password CHAR(32) NOT NULL,email varchar(70) NOT NULL,reg_time INT(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
CREATE TABLE <post> (pid INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,uid INT(4),uname VARCHAR(30) DEFAULT 'anonymous',content TEXT NOT NULL,post_time INT(11),ip CHAR(15) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
CREATE TABLE <reply> (rid INT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,pid INT(10) NOT NULL,content TEXT NOT NULL,r_time INT(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
CREATE TABLE <badip> (ip CHAR(15) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
CREATE TABLE <sysvar> (varname VARCHAR(20) NOT NULL,varvalue VARCHAR(255) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci
INSERT INTO <post> VALUES (NULL,NULL,'rainyjune','Welcome to our site.','{time}','{ip}')
INSERT INTO <sysvar> VALUES ('board_name','test')
INSERT INTO <sysvar> VALUES ('site_close','0')
INSERT INTO <sysvar> VALUES ('close_reason','Off line now.')
INSERT INTO <sysvar> VALUES ('admin_email','rainyjune@live.cn')
INSERT INTO <sysvar> VALUES ('copyright_info','Copyright © 2011 mapleleaf.googlecode.com')
INSERT INTO <sysvar> VALUES ('filter_words','fuck')
INSERT INTO <sysvar> VALUES ('valid_code_open','0')
INSERT INTO <sysvar> VALUES ('page_on','0')
INSERT INTO <sysvar> VALUES ('num_perpage','10')
INSERT INTO <sysvar> VALUES ('theme','simple')
INSERT INTO <sysvar> VALUES ('admin','{admin}')
INSERT INTO <sysvar> VALUES ('password','{adminpass}')
INSERT INTO <sysvar> VALUES ('lang','{lang}')
INSERT INTO <sysvar> VALUES ('timezone','8')
INSERT INTO <sysvar> VALUES ('filter_type','1')
INSERT INTO <sysvar> VALUES ('allowed_tags','&lt;a&gt;&lt;em&gt;&lt;strong&gt;&lt;cite&gt;&lt;code&gt;&lt;ul&gt;&lt;ol&gt;&lt;li&gt;&lt;dl&gt;&lt;dt&gt;&lt;dd&gt;')