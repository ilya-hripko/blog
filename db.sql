/*
SQLyog Ultimate v9.50 
MySQL - 5.5.31-0ubuntu0.12.04.2 : Database - test_blog
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `categories` */

insert  into `categories`(`id`,`parent_id`,`name`,`seo_title`,`seo_keywords`,`seo_description`,`text`) values (1,0,'Category 1','Category title 1','','','Ut non sapien rutrum neque viverra pulvinar vitae eu justo. Integer eget lorem sed erat imperdiet elementum nec ut ligula. Mauris sed leo et turpis congue posuere sed sit amet risus. Duis hendrerit felis nisl, at cursus augue vehicula quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce ante est, iaculis a efficitur et, malesuada in ipsum. Fusce id euismod dolor, at consectetur sapien. Proin sodales tempus turpis, sit amet sodales ex porttitor pretium. Proin gravida quam ut justo pellentesque vulputate eu sit amet neque. Curabitur velit felis, mattis quis semper id, rutrum vel arcu. Nunc volutpat luctus erat eget tincidunt. Nulla nec vehicula est. Mauris tincidunt ut neque in finibus. Donec fringilla purus risus, sit amet dignissim metus aliquet sit amet. '),(2,0,'Category 2','Category title 2','','',NULL);

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('fcf257271a7da8e1ae8b8f07b38fac38','192.168.0.15','Mozilla/5.0 (Windows NT 6.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36',1434038900,''),('088c1240acb1c76248d2d65805f235b0','192.168.0.15','Mozilla/5.0 (Windows NT 6.0; rv:38.0) Gecko/20100101 Firefox/38.0',1434054953,'a:6:{s:9:\"user_data\";s:0:\"\";s:7:\"user_id\";i:3;s:5:\"email\";s:18:\"test_user@test.com\";s:4:\"name\";s:9:\"User Test\";s:8:\"activate\";b:0;s:6:\"roleid\";s:1:\"2\";}');

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `author` varchar(50) NOT NULL,
  `is_moderated` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `comments` */

/*Table structure for table `crons` */

DROP TABLE IF EXISTS `crons`;

CREATE TABLE `crons` (
  `cron_name` varchar(255) NOT NULL,
  `started` int(11) DEFAULT NULL,
  `is_finished` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`cron_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `crons` */

/*Table structure for table `pages` */

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(50) NOT NULL,
  `is_index` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_menu` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `h1` varchar(255) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `keywords` varchar(500) DEFAULT NULL,
  `text` text,
  `updated_at` int(11) unsigned NOT NULL,
  `position` tinyint(2) unsigned NOT NULL DEFAULT '99',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `pages` */

insert  into `pages`(`id`,`url`,`is_index`,`is_menu`,`name`,`h1`,`title`,`description`,`keywords`,`text`,`updated_at`,`position`) values (1,'',1,1,'Home page','Test Blog','Test Blog','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl elit, vehicula sit amet blandit sit amet, rutrum in nulla. Aenean vulputate nec sapien ut dapibus. Sed gravida rutrum lectus. Proin tempor venenatis dui eu imperdiet. Donec vel nisl tempor, rhoncus libero eget, ultrices nisi. Mauris sed metus metus. Phasellus condimentum nisi et diam porttitor, at cursus mi placerat. Etiam ac massa ut nibh tincidunt pretium varius non nulla. Vivamus in lectus ac libero accumsan sagittis eu eget ','test blog, home page','<p><b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Donec nisl elit, vehicula sit amet blandit sit amet, rutrum in nulla. Aenean vulputate nec sapien ut dapibus. Sed gravida rutrum lectus. Proin tempor venenatis dui eu imperdiet. Donec vel nisl tempor, rhoncus libero eget, ultrices nisi. Mauris sed metus metus. Phasellus condimentum nisi et diam porttitor, at cursus mi placerat. Etiam ac massa ut nibh tincidunt pretium varius non nulla. Vivamus in lectus ac libero accumsan sagittis eu eget leo. Proin vitae ligula tortor. Etiam suscipit pretium ligula, at porttitor nulla bibendum ut. Aenean eu varius eros. Nam purus dolor, tristique a sapien sed, luctus lobortis felis. Nullam placerat consectetur magna a aliquam. Duis et mattis dolor. Mauris pharetra suscipit magna, vel posuere mauris.</p>\r\n<p>Ut non sapien rutrum neque viverra pulvinar vitae eu justo. Integer eget lorem sed erat imperdiet elementum nec ut ligula. Mauris sed leo et turpis congue posuere sed sit amet risus. Duis hendrerit felis nisl, at cursus augue vehicula quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce ante est, iaculis a efficitur et, malesuada in ipsum. Fusce id euismod dolor, at consectetur sapien. Proin sodales tempus turpis, sit amet sodales ex porttitor pretium. Proin gravida quam ut justo pellentesque vulputate eu sit amet neque. Curabitur velit felis, mattis quis semper id, rutrum vel arcu. Nunc volutpat luctus erat eget tincidunt. Nulla nec vehicula est. Mauris tincidunt ut neque in finibus. Donec fringilla purus risus, sit amet dignissim metus aliquet sit amet.</p>',1431095880,0),(2,'test-page',0,1,'Test page','Test page','Test Page - Testblog.com','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl elit, vehicula sit amet blandit sit amet, rutrum in nulla. Aenean vulputate nec sapien ut dapibus. Sed gravida rutrum lectus. Proin tempor venenatis dui eu imperdiet. Donec vel nisl tempor, rhoncus libero eget, ultrices nisi. Mauris sed metus metus. Phasellus condimentum nisi et diam porttitor, at cursus mi placerat. Etiam ac massa ut nibh tincidunt pretium varius non nulla. Vivamus in lectus ac libero accumsan sagittis eu eget ','test blog, test page','<p><b>Lorem ipsum dolor sit amet</b>, consectetur adipiscing elit. Donec nisl elit, vehicula sit amet blandit sit amet, rutrum in nulla. Aenean vulputate nec sapien ut dapibus. Sed gravida rutrum lectus. Proin tempor venenatis dui eu imperdiet. Donec vel nisl tempor, rhoncus libero eget, ultrices nisi. Mauris sed metus metus. Phasellus condimentum nisi et diam porttitor, at cursus mi placerat. Etiam ac massa ut nibh tincidunt pretium varius non nulla. Vivamus in lectus ac libero accumsan sagittis eu eget leo. Proin vitae ligula tortor. Etiam suscipit pretium ligula, at porttitor nulla bibendum ut. Aenean eu varius eros. Nam purus dolor, tristique a sapien sed, luctus lobortis felis. Nullam placerat consectetur magna a aliquam. Duis et mattis dolor. Mauris pharetra suscipit magna, vel posuere mauris.</p>\r\n<p>Ut non sapien rutrum neque viverra pulvinar vitae eu justo. Integer eget lorem sed erat imperdiet elementum nec ut ligula. Mauris sed leo et turpis congue posuere sed sit amet risus. Duis hendrerit felis nisl, at cursus augue vehicula quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce ante est, iaculis a efficitur et, malesuada in ipsum. Fusce id euismod dolor, at consectetur sapien. Proin sodales tempus turpis, sit amet sodales ex porttitor pretium. Proin gravida quam ut justo pellentesque vulputate eu sit amet neque. Curabitur velit felis, mattis quis semper id, rutrum vel arcu. Nunc volutpat luctus erat eget tincidunt. Nulla nec vehicula est. Mauris tincidunt ut neque in finibus. Donec fringilla purus risus, sit amet dignissim metus aliquet sit amet.</p>\r\n<p>Cras elit turpis, lobortis sit amet blandit efficitur, ultricies in elit. Quisque vitae arcu turpis. In ut cursus metus. Curabitur ut dui gravida, ultrices risus nec, semper urna. Morbi in sem vel leo vulputate mattis. Maecenas fringilla tincidunt odio ac consectetur. Nulla cursus, libero at fermentum pharetra, nibh nisi ultricies felis, ac ultrices felis neque eu quam. Curabitur id lobortis augue. Proin convallis lectus sed tincidunt pellentesque. Nulla accumsan risus enim, ut hendrerit nisl congue et. Suspendisse placerat fringilla nulla, id egestas ante lobortis id. Duis at nisi mi. Aliquam ullamcorper mattis tortor, at fermentum lectus interdum quis. Integer eu imperdiet lorem. Mauris aliquet, lorem nec posuere rutrum, ipsum libero finibus sapien, non vulputate turpis enim ac purus.</p>\r\n<p>Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus et feugiat odio. In tincidunt laoreet felis, nec pharetra nibh bibendum eget. Quisque vehicula eu mi vehicula dignissim. Aliquam velit nulla, eleifend vel eros vel, luctus accumsan ex. Nulla non dignissim lacus.</p>\r\n<p>Nulla congue, magna vel sagittis placerat, dui tellus bibendum ipsum, sodales suscipit elit tortor non massa. Quisque nunc urna, aliquam et sapien malesuada, consectetur convallis urna. Mauris in justo in nisl congue tincidunt. Duis hendrerit tortor felis, in fringilla enim efficitur eu. Vestibulum mattis malesuada neque eget suscipit. Sed aliquam dui a nisl ultricies, nec feugiat erat consequat. Ut viverra maximus sapien scelerisque mattis. Duis non nisi in urna tincidunt rhoncus. Nullam scelerisque vehicula bibendum. Integer et mauris interdum, ultrices ex at, finibus enim. Nam sit amet dolor eu quam molestie condimentum a id leo. Ut porta, arcu ac porta ultricies, mi lorem fringilla risus, vel euismod mi magna vitae dui. Nunc dolor enim, efficitur a enim vel, varius imperdiet erat. Sed non facilisis augue. Nullam placerat vel nisi vitae pharetra. </p>',1431095880,10),(3,'posts',0,1,'Posts','Posts','Posts','posts description','posts, kewords',NULL,1431095880,5),(6,'contacts',0,1,'Contacts','Contacts','Contacts','','','<div class=\"google-map\"><iframe width=\"700\" height=\"300\" frameborder=\"0\" scrolling=\"no\" marginheight=\"0\" marginwidth=\"0\" src=\"http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Brooklyn,+NY,+USA&amp;aq=0&amp;sll=37.0625,-95.677068&amp;sspn=47.704107,79.013672&amp;ie=UTF8&amp;hq=&amp;hnear=Brooklyn,+Kings,+New+York&amp;ll=40.649974,-73.949919&amp;spn=0.01628,0.028238&amp;z=14&amp;iwloc=A&amp;output=embed\"></iframe></div>\r\n<h5>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore.</h5>\r\n<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque la udantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas.</p>\r\n<dl class=\"address\">\r\n<dt>The Company Name Inc.<br>\r\n9870 St Vincent Place,<br>\r\nGlasgow, DC 45 Fr 45.</dt>\r\n<dd>\r\n<ul>\r\n<li><span>Telephone:</span>+1 800 603 6035</li>\r\n<li><span>FAX:</span>+1 800 889 9898</li>\r\n<li>E-mail: <a href=\"#\">mail@demolink.org</a></li>\r\n</ul>\r\n</dd>\r\n</dl>',1431095880,50);

/*Table structure for table `pages2comments` */

DROP TABLE IF EXISTS `pages2comments`;

CREATE TABLE `pages2comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(3) unsigned NOT NULL,
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pages2comments_to_page` (`page_id`),
  KEY `FK_pages2comments_to_comments` (`comment_id`),
  CONSTRAINT `FK_pages2comments_to_page` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pages2comments_to_comments` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `pages2comments` */

/*Table structure for table `post` */

DROP TABLE IF EXISTS `post`;

CREATE TABLE `post` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `post_crop` varchar(1000) NOT NULL,
  `post` longtext NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(500) DEFAULT NULL,
  `seo_description` varchar(500) DEFAULT NULL,
  `rating_score` float(9,2) unsigned NOT NULL DEFAULT '5.00',
  `rating_count` int(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `post` */

insert  into `post`(`id`,`title`,`post_crop`,`post`,`created_at`,`updated_at`,`seo_title`,`seo_keywords`,`seo_description`,`rating_score`,`rating_count`) values (1,'Test post 1 ','Ut non sapien rutrum neque viverra pulvinar vitae eu justo. Integer eget lorem sed erat imperdiet elementum nec ut ligula. Mauris sed leo et turpis congue posuere sed sit amet risus. Duis hendrerit felis nisl, at cursus augue vehicula quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce ante est, iaculis a efficitur et, malesuada in ipsum. Fusce id euismod dolor, at consectetur sapien. Proin sodales tempus turpis, sit amet sodales ex porttitor pretium. Proin gravida quam ut justo pellentesque vulputate eu sit amet neque. Curabitur velit felis, mattis quis semper id, rutrum vel arcu. Nunc volutpat luctus erat eget tincidunt. Nulla nec vehicula est. Mauris tincidunt ut neque in finibus. Donec fringilla purus risus, sit amet dignissim metus aliquet sit amet. ','<p>Cras elit turpis, lobortis sit amet blandit efficitur, ultricies in elit. Quisque vitae arcu turpis. In ut cursus metus. Curabitur ut dui gravida, ultrices risus nec, semper urna. Morbi in sem vel leo vulputate mattis. Maecenas fringilla tincidunt odio ac consectetur. Nulla cursus, libero at fermentum pharetra, nibh nisi ultricies felis, ac ultrices felis neque eu quam. Curabitur id lobortis augue. Proin convallis lectus sed tincidunt pellentesque. Nulla accumsan risus enim, ut hendrerit nisl congue et. Suspendisse placerat fringilla nulla, id egestas ante lobortis id. Duis at nisi mi. Aliquam ullamcorper mattis tortor, at fermentum lectus interdum quis. Integer eu imperdiet lorem. Mauris aliquet, lorem nec posuere rutrum, ipsum libero finibus sapien, non vulputate turpis enim ac purus.</p>\r\n<p>Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus et feugiat odio. In tincidunt laoreet felis, nec pharetra nibh bibendum eget. Quisque vehicula eu mi vehicula dignissim. Aliquam velit nulla, eleifend vel eros vel, luctus accumsan ex. Nulla non dignissim lacus.</p>\r\n<p>Nulla congue, magna vel sagittis placerat, dui tellus bibendum ipsum, sodales suscipit elit tortor non massa. Quisque nunc urna, aliquam et sapien malesuada, consectetur convallis urna. Mauris in justo in nisl congue tincidunt. Duis hendrerit tortor felis, in fringilla enim efficitur eu. Vestibulum mattis malesuada neque eget suscipit. Sed aliquam dui a nisl ultricies, nec feugiat erat consequat. Ut viverra maximus sapien scelerisque mattis. Duis non nisi in urna tincidunt rhoncus. Nullam scelerisque vehicula bibendum. Integer et mauris interdum, ultrices ex at, finibus enim. Nam sit amet dolor eu quam molestie condimentum a id leo. Ut porta, arcu ac porta ultricies, mi lorem fringilla risus, vel euismod mi magna vitae dui. Nunc dolor enim, efficitur a enim vel, varius imperdiet erat. Sed non facilisis augue. Nullam placerat vel nisi vitae pharetra. </p>',1431373680,1431373680,'title first post','','',5.00,1),(2,'Post test 2','<b>Cras elit turpis, lobortis sit</b> amet blandit efficitur, ultricies in elit. Quisque vitae arcu turpis. In ut cursus metus. Curabitur ut dui gravida, ultrices risus nec, semper urna. Morbi in sem vel leo vulputate mattis. Maecenas fringilla tincidunt odio ac consectetur. Nulla cursus, libero at fermentum pharetra, nibh nisi ultricies felis, ac ultrices felis neque eu quam. Curabitur id lobortis augue. Proin convallis lectus sed tincidunt pellentesque. Nulla accumsan risus enim, ut hendrerit nisl congue et. Suspendisse placerat fringilla nulla, id egestas ante lobortis id. Duis at nisi mi. Aliquam ullamcorper mattis tortor, at fermentum lectus interdum quis. Integer eu imperdiet lorem. Mauris aliquet, lorem nec posuere rutrum, ipsum libero finibus sapien, non vulputate turpis enim ac purus.','<p>Cras elit turpis, lobortis sit amet blandit efficitur, ultricies in elit. Quisque vitae arcu turpis. In ut cursus metus. Curabitur ut dui gravida, ultrices risus nec, semper urna. Morbi in sem vel leo vulputate mattis. Maecenas fringilla tincidunt odio ac consectetur. Nulla cursus, libero at fermentum pharetra, nibh nisi ultricies felis, ac ultrices felis neque eu quam. Curabitur id lobortis augue. Proin convallis lectus sed tincidunt pellentesque. Nulla accumsan risus enim, ut hendrerit nisl congue et. Suspendisse placerat fringilla nulla, id egestas ante lobortis id. Duis at nisi mi. Aliquam ullamcorper mattis tortor, at fermentum lectus interdum quis. Integer eu imperdiet lorem. Mauris aliquet, lorem nec posuere rutrum, ipsum libero finibus sapien, non vulputate turpis enim ac purus.</p>\n<p>Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus et feugiat odio. In tincidunt laoreet felis, nec pharetra nibh bibendum eget. Quisque vehicula eu mi vehicula dignissim. Aliquam velit nulla, eleifend vel eros vel, luctus accumsan ex. Nulla non dignissim lacus.</p>\n<p>Nulla congue, magna vel sagittis placerat, dui tellus bibendum ipsum, sodales suscipit elit tortor non massa. Quisque nunc urna, aliquam et sapien malesuada, consectetur convallis urna. Mauris in justo in nisl congue tincidunt. Duis hendrerit tortor felis, in fringilla enim efficitur eu. Vestibulum mattis malesuada neque eget suscipit. Sed aliquam dui a nisl ultricies, nec feugiat erat consequat. Ut viverra maximus sapien scelerisque mattis. Duis non nisi in urna tincidunt rhoncus. Nullam scelerisque vehicula bibendum. Integer et mauris interdum, ultrices ex at, finibus enim. Nam sit amet dolor eu quam molestie condimentum a id leo. Ut porta, arcu ac porta ultricies, mi lorem fringilla risus, vel euismod mi magna vitae dui. Nunc dolor enim, efficitur a enim vel, varius imperdiet erat. Sed non facilisis augue. Nullam placerat vel nisi vitae pharetra. </p>',1431375680,1434053388,'title post 2','seo keywords, ...','seo description',4.00,2),(3,'test add post','Nulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus et feugiat odio. In tincidunt laoreet felis, nec pharetra nibh bibendum eget. Quisque vehicula eu mi vehicula dignissim. Aliquam velit nulla, eleifend vel eros vel, luctus accumsan ex. Nulla non dignissim lacus. ','\n<p>\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Donec nisl elit, vehicula sit amet blandit sit amet, rutrum in nulla. Aenean vulputate nec sapien ut dapibus. Sed gravida rutrum lectus. Proin tempor venenatis dui eu imperdiet. Donec vel nisl tempor, rhoncus libero eget, ultrices nisi. Mauris sed metus metus. Phasellus condimentum nisi et diam porttitor, at cursus mi placerat. Etiam ac massa ut nibh tincidunt pretium varius non nulla. Vivamus in lectus ac libero accumsan sagittis eu eget leo. Proin vitae ligula tortor. Etiam suscipit pretium ligula, at porttitor nulla bibendum ut. Aenean eu varius eros. Nam purus dolor, tristique a sapien sed, luctus lobortis felis. Nullam placerat consectetur magna a aliquam. Duis et mattis dolor. Mauris pharetra suscipit magna, vel posuere mauris.\n</p>\n<p>\nUt non sapien rutrum neque viverra pulvinar vitae eu justo. Integer eget lorem sed erat imperdiet elementum nec ut ligula. Mauris sed leo et turpis congue posuere sed sit amet risus. Duis hendrerit felis nisl, at cursus augue vehicula quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Fusce ante est, iaculis a efficitur et, malesuada in ipsum. Fusce id euismod dolor, at consectetur sapien. Proin sodales tempus turpis, sit amet sodales ex porttitor pretium. Proin gravida quam ut justo pellentesque vulputate eu sit amet neque. Curabitur velit felis, mattis quis semper id, rutrum vel arcu. Nunc volutpat luctus erat eget tincidunt. Nulla nec vehicula est. Mauris tincidunt ut neque in finibus. Donec fringilla purus risus, sit amet dignissim metus aliquet sit amet.\n</p>\n<p>\nCras elit turpis, lobortis sit amet blandit efficitur, ultricies in elit. Quisque vitae arcu turpis. In ut cursus metus. Curabitur ut dui gravida, ultrices risus nec, semper urna. Morbi in sem vel leo vulputate mattis. Maecenas fringilla tincidunt odio ac consectetur. Nulla cursus, libero at fermentum pharetra, nibh nisi ultricies felis, ac ultrices felis neque eu quam. Curabitur id lobortis augue. Proin convallis lectus sed tincidunt pellentesque. Nulla accumsan risus enim, ut hendrerit nisl congue et. Suspendisse placerat fringilla nulla, id egestas ante lobortis id. Duis at nisi mi. Aliquam ullamcorper mattis tortor, at fermentum lectus interdum quis. Integer eu imperdiet lorem. Mauris aliquet, lorem nec posuere rutrum, ipsum libero finibus sapien, non vulputate turpis enim ac purus.\n</p>\n<p>\nNulla facilisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus et feugiat odio. In tincidunt laoreet felis, nec pharetra nibh bibendum eget. Quisque vehicula eu mi vehicula dignissim. Aliquam velit nulla, eleifend vel eros vel, luctus accumsan ex. Nulla non dignissim lacus.\n</p>\n<p>\nNulla congue, magna vel sagittis placerat, dui tellus bibendum ipsum, sodales suscipit elit tortor non massa. Quisque nunc urna, aliquam et sapien malesuada, consectetur convallis urna. Mauris in justo in nisl congue tincidunt. Duis hendrerit tortor felis, in fringilla enim efficitur eu. Vestibulum mattis malesuada neque eget suscipit. Sed aliquam dui a nisl ultricies, nec feugiat erat consequat. Ut viverra maximus sapien scelerisque mattis. Duis non nisi in urna tincidunt rhoncus. Nullam scelerisque vehicula bibendum. Integer et mauris interdum, ultrices ex at, finibus enim. Nam sit amet dolor eu quam molestie condimentum a id leo. Ut porta, arcu ac porta ultricies, mi lorem fringilla risus, vel euismod mi magna vitae dui. Nunc dolor enim, efficitur a enim vel, varius imperdiet erat. Sed non facilisis augue. Nullam placerat vel nisi vitae pharetra.\n</p>',1434053984,1434053984,'seo title of test post','seo key','seo desc',5.00,1);

/*Table structure for table `post2category` */

DROP TABLE IF EXISTS `post2category`;

CREATE TABLE `post2category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post2category_to_post` (`post_id`),
  KEY `FK_post2category_to_category` (`category_id`),
  CONSTRAINT `FK_post2category_to_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_post2category_to_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `post2category` */

insert  into `post2category`(`id`,`post_id`,`category_id`) values (1,1,1),(2,2,2),(3,3,1);

/*Table structure for table `post2comments` */

DROP TABLE IF EXISTS `post2comments`;

CREATE TABLE `post2comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL,
  `comment_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post2comments_to_post` (`post_id`),
  KEY `FK_post2comments_to_comment` (`comment_id`),
  CONSTRAINT `FK_post2comments_to_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_post2comments_to_comments` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `post2comments` */

/*Table structure for table `post2user` */

DROP TABLE IF EXISTS `post2user`;

CREATE TABLE `post2user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_post2user_to_users` (`user_id`),
  KEY `FK_post2user_to_post` (`post_id`),
  CONSTRAINT `FK_post2user_to_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_post2user_to_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `post2user` */

insert  into `post2user`(`id`,`post_id`,`user_id`) values (1,1,2),(2,2,2),(3,3,2);

/*Table structure for table `tbl_acl` */

DROP TABLE IF EXISTS `tbl_acl`;

CREATE TABLE `tbl_acl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('role','user') NOT NULL,
  `type_id` int(11) NOT NULL,
  `resource_id` int(11) NOT NULL,
  `action` enum('allow','deny') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tbl_acl_resurces` (`resource_id`),
  KEY `FK_tbl_acl_type` (`type_id`),
  CONSTRAINT `FK_tbl_acl_resurces` FOREIGN KEY (`resource_id`) REFERENCES `tbl_aclresources` (`id`),
  CONSTRAINT `FK_tbl_acl_type` FOREIGN KEY (`type_id`) REFERENCES `tbl_aclroles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_acl` */

insert  into `tbl_acl`(`id`,`type`,`type_id`,`resource_id`,`action`) values (1,'role',1,1,'allow'),(2,'role',4,2,'allow'),(3,'role',1,2,'allow'),(4,'role',4,1,'deny');

/*Table structure for table `tbl_aclresources` */

DROP TABLE IF EXISTS `tbl_aclresources`;

CREATE TABLE `tbl_aclresources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `resource` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `aclgroup` varchar(255) NOT NULL,
  `aclgrouporder` int(11) NOT NULL,
  `default_value` enum('true','false') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_aclresources` */

insert  into `tbl_aclresources`(`id`,`resource`,`description`,`aclgroup`,`aclgrouporder`,`default_value`) values (1,'super admin panel','','',0,'false'),(2,'cabinet','','',0,'');

/*Table structure for table `tbl_aclroles` */

DROP TABLE IF EXISTS `tbl_aclroles`;

CREATE TABLE `tbl_aclroles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `roleorder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `tbl_aclroles` */

insert  into `tbl_aclroles`(`id`,`name`,`roleorder`) values (1,'Super Admin',1),(2,'User',0),(4,'Moderator',0);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `roleid` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `date_created` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL,
  `patronymic` varchar(100) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `FK_users_role` (`roleid`),
  CONSTRAINT `FK_users_role` FOREIGN KEY (`roleid`) REFERENCES `tbl_aclroles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`user_id`,`roleid`,`email`,`password`,`salt`,`activation_code`,`forgotten_password_code`,`forgotten_password_time`,`first_name`,`last_name`,`name`,`ip_address`,`date_created`,`last_login`,`active`,`patronymic`,`remember_code`,`phone`) values (2,2,'ilya.hripko@gmail.com','09713c86fd40eef3f04faab8c87fde278cccdbb7',NULL,NULL,NULL,NULL,'','','Ilya','192.168.0.15',1434044404,1434054937,1,NULL,'5c749ff6d01544b7e12f651a8ebcfdf91cf47863',NULL),(3,2,'test_user@test.com','e8044eb371f57b0246fee9085d7eed45065f17f3',NULL,NULL,NULL,NULL,'','','User Test','192.168.0.15',1434054976,1434054976,1,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
