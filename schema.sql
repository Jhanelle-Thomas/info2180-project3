/*Project 3 SQL file*/
/*Date: November 24,2015 */
/*Group: Musketers*/

DROP DATABASE IF EXISTS schema_db;
CREATE DATABASE schema_db;
USE schema_db;

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User`
(
`id` int(10) NOT NULL auto_increment, 
`firstname` varchar(20) NOT NULL,
`lastname` varchar(20) NOT NULL,
`username`varchar(10) NOT NULL,
`password` varchar(255) NOT NULL,
PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Message`;
CREATE TABLE `Message`
(
`id` int (10) NOT NULL auto_increment,
`recipient_ids` varchar(20) NOT NULL,
`user_id` int(10) NOT NULL,
`subject` varchar(255) NOT NULL,
`message_body` varchar(255) NOT NULL,
`date_sent` varchar(30) NOT NULL,
PRIMARY KEY (`id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `Message_read`;
CREATE TABLE `Message_read`
(
`id`int(10) NOT NULL auto_increment,
`message_id` int(10) NOT NULL,
`reader_id` varchar(10) NOT NULL,
`date_read` varchar(30) NOT NULL,
PRIMARY KEY (`id`) 
)ENGINE=MyISAM DEFAULT CHARSET=utf8;