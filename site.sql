/*
Navicat MySQL Data Transfer

Source Server         : OpensimAds
Source Server Version : 50537
Source Host           : localhost:3306
Source Database       : site

Target Server Type    : MYSQL
Target Server Version : 50537
File Encoding         : 65001

Date: 2014-10-31 21:52:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for avatar_actions
-- ----------------------------
DROP TABLE IF EXISTS `avatar_actions`;
CREATE TABLE `avatar_actions` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `grid` varchar(255) NOT NULL,
  `dbid` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `toucher` varchar(255) NOT NULL,
  `firsttouchtime` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `timesclaimed` varchar(255) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of avatar_actions
-- ----------------------------

-- ----------------------------
-- Table structure for avatars
-- ----------------------------
DROP TABLE IF EXISTS `avatars`;
CREATE TABLE `avatars` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `grid` varchar(255) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `osaid` int(11) DEFAULT NULL,
  `cash` varchar(255) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of avatars
-- ----------------------------
INSERT INTO `avatars` VALUES ('1', 'InWorldz', '8f7a347e-02f1-4611-9667-286acd6262ec', 'Chrisx Vortex', null, '0.00');

-- ----------------------------
-- Table structure for grids
-- ----------------------------
DROP TABLE IF EXISTS `grids`;
CREATE TABLE `grids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grid` varchar(255) NOT NULL,
  `shortname` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `loginuri` varchar(255) DEFAULT NULL,
  `hguri` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of grids
-- ----------------------------
INSERT INTO `grids` VALUES ('1', 'InWorldz', 'InWorldz', 'http://inworldz.com/', 'http://login.inworldz.com:8002/', null);

-- ----------------------------
-- Table structure for stores
-- ----------------------------
DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `grid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sim` varchar(255) NOT NULL,
  `pos` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of stores
-- ----------------------------

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `grid` varchar(255) NOT NULL,
  `dbid` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `ownername` varchar(255) NOT NULL,
  `sim` varchar(255) NOT NULL,
  `parcel` varchar(255) NOT NULL,
  `pos` varchar(255) NOT NULL,
  `primkey` varchar(255) NOT NULL,
  `tokenworth` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL DEFAULT '0.00',
  `claims` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tokens
-- ----------------------------

-- ----------------------------
-- Table structure for zw_emailconfirm
-- ----------------------------
DROP TABLE IF EXISTS `zw_emailconfirm`;
CREATE TABLE `zw_emailconfirm` (
  `id` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `isnewuser` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_emailconfirm
-- ----------------------------

-- ----------------------------
-- Table structure for zw_mainmenu
-- ----------------------------
DROP TABLE IF EXISTS `zw_mainmenu`;
CREATE TABLE `zw_mainmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `childof` varchar(255) NOT NULL DEFAULT '0',
  `sortby` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_mainmenu
-- ----------------------------
INSERT INTO `zw_mainmenu` VALUES ('1', 'Products', '', '0', '1');
INSERT INTO `zw_mainmenu` VALUES ('2', 'Token Hunt', 'tokenhunt.php', '1', '1');

-- ----------------------------
-- Table structure for zw_news
-- ----------------------------
DROP TABLE IF EXISTS `zw_news`;
CREATE TABLE `zw_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `msg` longtext,
  `time` varchar(255) DEFAULT NULL,
  `edit_time` varchar(255) DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_news
-- ----------------------------
INSERT INTO `zw_news` VALUES ('1', 'Please mind the mess', 'Hello everyone viewing this site. I am Chris Strachan aka Chrisx Vortex in InWorldz and Christina Vortex on other opensim grids. I was a volunteer at Zetamex and just recently quit due to personal reasons and to pursue other dreams, this site is one of them. Below is a short FAQ for everyone to better understand about this new venture of mine.\r\n\r\n&lt;B&gt;What is Opensim Ads?&lt;/B&gt;\r\nIt will consist of in world products ranging from traffic generators and campers to vendors and rentals. Everything that a store and mall owners may want to help their place of business while giving poor avatars a way to have fun without spending real life money.\r\n\r\n&lt;B&gt;What personal info do you require?&lt;/B&gt;\r\nJust a valid email address to join this site once registration is open. We automatically store your avatar&#039;s name, UUID key and grid your avatar is on. When you join and want to link your avatars with your opensim ads account, there is a API key that you can generate and use that at any of our in world ATM&#039;s. \r\n\r\n&lt;B&gt;What is a API Key?&lt;/B&gt;\r\nUnlike some web services that ask for the email address you used to join their site to identify you, we use a unique API key that consist of random letters and numbers to identify you that you can generate over and over again at any time.\r\n\r\n&lt;B&gt;What if I have more questions?&lt;/B&gt;\r\nRight now there is no way to contact us via this site however please feel free to IM me in InWorldz and Second Life if you have any questions.&lt;br&gt;\r\nInWorldz: Chrisx Vortex\r\nSecondLife: VenumusVortex Resident\r\n\r\nThank you for visiting this site. Sorry that there is not much here yet as i am still working on this site. Hope to see you soon.', '1414715134', '1414715341', '1', '');

-- ----------------------------
-- Table structure for zw_resetcode
-- ----------------------------
DROP TABLE IF EXISTS `zw_resetcode`;
CREATE TABLE `zw_resetcode` (
  `id` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `expiry` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_resetcode
-- ----------------------------

-- ----------------------------
-- Table structure for zw_sessions
-- ----------------------------
DROP TABLE IF EXISTS `zw_sessions`;
CREATE TABLE `zw_sessions` (
  `id` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_sessions
-- ----------------------------
INSERT INTO `zw_sessions` VALUES ('1', 'Kv0bVdIv92OgDwF', '1414806680');

-- ----------------------------
-- Table structure for zw_settings
-- ----------------------------
DROP TABLE IF EXISTS `zw_settings`;
CREATE TABLE `zw_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `info` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_settings
-- ----------------------------
INSERT INTO `zw_settings` VALUES ('1', 'SiteAddress', 'http://www.opensimads.com', 'Address of site', 'site');
INSERT INTO `zw_settings` VALUES ('2', 'admin_level', '5', 'Level required for admin stuff', 'site');
INSERT INTO `zw_settings` VALUES ('3', 'SiteName', 'OpensimAds', '', 'site');
INSERT INTO `zw_settings` VALUES ('4', 'SiteEmail', 'noreply@opensimads.com', 'Site email address', 'site');
INSERT INTO `zw_settings` VALUES ('5', 'DisqusShortName', 'opensimads', 'Disqus Short Name', 'site');
INSERT INTO `zw_settings` VALUES ('6', 'min_password', '3', '', 'site');
INSERT INTO `zw_settings` VALUES ('7', 'max_password', '15', '', 'site');
INSERT INTO `zw_settings` VALUES ('8', 'cookie_domain', 'opensimads.com', 'Cookie Domain', 'site');
INSERT INTO `zw_settings` VALUES ('9', 'cookie_path', '/', 'Cookie Path', 'site');
INSERT INTO `zw_settings` VALUES ('10', 'cookie_prefix', 'zs_', 'Cookie Prefix. Default is zs_', 'site');
INSERT INTO `zw_settings` VALUES ('11', 'activation_type', '2', 'Activation Type', 'site');
INSERT INTO `zw_settings` VALUES ('12', 'security_image', 'yes', 'Use Recaptcha', 'site');
INSERT INTO `zw_settings` VALUES ('13', 'ReCaptcha_Private_Key', '6Lco7vwSAAAAAI97r6UVKh5ygUtAf4GhRfhnm0ys', 'Recaptcha Private Key', 'site');
INSERT INTO `zw_settings` VALUES ('14', 'ReCaptcha_Public_Key', '6Lco7vwSAAAAAIqLDnuwwjTOnL_0YU0LFhFsF1rT', 'Recaptcha Public Key', 'site');
INSERT INTO `zw_settings` VALUES ('15', 'TimeZone', 'America/Toronto', 'Time Zone', 'site');
INSERT INTO `zw_settings` VALUES ('16', 'logout_redirect', 'index.php', 'Logout Redirect', 'site');
INSERT INTO `zw_settings` VALUES ('17', 'AllowRegistration', 'n', 'Allow people to register?', 'site');
INSERT INTO `zw_settings` VALUES ('18', 'Twitter', '', 'Twitter', 'site');
INSERT INTO `zw_settings` VALUES ('19', 'TwitterAPIKey', '', 'Twitter API key', 'site');
INSERT INTO `zw_settings` VALUES ('20', 'redirect_type', '1', 'Redirect Type', 'site');
INSERT INTO `zw_settings` VALUES ('21', 'cookie_length', '2592000', 'How long before the cookie expires in unix seconds', 'site');
INSERT INTO `zw_settings` VALUES ('22', 'APITokenLength', '8', 'number of characters in the api token', 'api');

-- ----------------------------
-- Table structure for zw_settings_menu
-- ----------------------------
DROP TABLE IF EXISTS `zw_settings_menu`;
CREATE TABLE `zw_settings_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_settings_menu
-- ----------------------------
INSERT INTO `zw_settings_menu` VALUES ('1', 'site');
INSERT INTO `zw_settings_menu` VALUES ('2', 'api');

-- ----------------------------
-- Table structure for zw_users
-- ----------------------------
DROP TABLE IF EXISTS `zw_users`;
CREATE TABLE `zw_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `session` varchar(255) NOT NULL,
  `last_login` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL,
  `created` varchar(255) NOT NULL,
  `last_action` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zw_users
-- ----------------------------
INSERT INTO `zw_users` VALUES ('1', 'Chrisx84', 'chrisx84@live.ca', '3991d7210dd5a784290fbaa76de096e2', 'c5f4a57a7ecc20b8e8060e076dbc338c', 'Kv0bVdIv92OgDwF', '1414623542', '5', '1414450860', '1414806680', '28W7FfkV');
