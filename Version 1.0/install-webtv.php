<?php
/**
Install Addon WebTV
**/

include("_mysql.php");
include("_settings.php");
include("_functions.php");

echo "<center><h1>Installation de l'addon WebTV - Version 1</h1></center>";

mysql_query("DROP TABLE IF EXISTS `".PREFIX."stream`");
mysql_query("CREATE TABLE `".PREFIX."stream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `desc` varchar(250) NOT NULL,
  `type` int(11) NOT NULL,
  `lien` varchar(50) NOT NULL,
  `image` varchar(25) NOT NULL DEFAULT 'aucun.png',
  `sort` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
)");

mysql_query("DROP TABLE IF EXISTS `".PREFIX."stream_type`");
mysql_query("CREATE TABLE `".PREFIX."stream_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
)");
mysql_query("INSERT INTO `".PREFIX."stream_type` (`id`, `name`) VALUES
(1, 'Dailymotion'),
(2, 'Twitch');");

echo "<center><h1>Addon cr&eacute;er par : Gloird </h1></center>";

?>