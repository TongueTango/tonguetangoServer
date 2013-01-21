-- --------------------------------------------------------
-- Host:                         tango-production.cmnhg5thxftx.us-east-1.rds.amazonaws.com
-- Server version:               5.5.27-log - Source distribution
-- Server OS:                    Linux
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2013-01-21 21:48:54
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for tango
CREATE DATABASE IF NOT EXISTS `tango` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tango`;


-- Dumping structure for table tango.address_book
CREATE TABLE IF NOT EXISTS `address_book` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `contact_email` varchar(50) NOT NULL,
  `contact_phone` varchar(200) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_address_book_relation` (`user_id`,`contact_email`) USING BTREE,
  KEY `FK_address_book_user` (`user_id`) USING BTREE,
  CONSTRAINT `FK_address_book_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.blocks
CREATE TABLE IF NOT EXISTS `blocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `blocked_user_id` int(10) NOT NULL,
  `blocked` int(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.contacts
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `contact_user_id` int(10) unsigned NOT NULL,
  `accepted` int(1) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_contact_relation` (`user_id`,`contact_user_id`),
  KEY `FK_contacts-user` (`user_id`),
  KEY `FK_contacts-contact_user` (`contact_user_id`),
  CONSTRAINT `FK_contacts-contact_user` FOREIGN KEY (`contact_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_contacts-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.contents
CREATE TABLE IF NOT EXISTS `contents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_type_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `data` text,
  `iphone_5data` text,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_contents-content_types` (`content_type_id`),
  KEY `FK_contents-products` (`product_id`),
  CONSTRAINT `FK_contents-content_types` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_contents-products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.content_types
CREATE TABLE IF NOT EXISTS `content_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.devices
CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `device_type` varchar(45) NOT NULL,
  `push_token` varchar(128) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `unique_id` varchar(255) NOT NULL,
  `auth_token` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_devices-user` (`user_id`),
  KEY `unique_id_UNIQUE` (`unique_id`),
  CONSTRAINT `FK_devices-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.facebook_friends
CREATE TABLE IF NOT EXISTS `facebook_friends` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `facebook_id` varchar(50) NOT NULL,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_facebook_friend_relation` (`user_id`,`facebook_id`) USING BTREE,
  KEY `FK_facebook_friends_user` (`user_id`) USING BTREE,
  CONSTRAINT `FK_facebook_friends_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_groups-user` (`user_id`),
  CONSTRAINT `FK_groups-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.group_blocks
CREATE TABLE IF NOT EXISTS `group_blocks` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `blocked` int(1) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_group_blocks_relation` (`user_id`,`group_id`) USING BTREE,
  KEY `FK_group_blocks_user` (`user_id`) USING BTREE,
  KEY `FK_group_blocks_group` (`group_id`) USING BTREE,
  CONSTRAINT `FK_group_blocks_group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_group_blocks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.group_members
CREATE TABLE IF NOT EXISTS `group_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `accepted` int(11) NOT NULL DEFAULT '0',
  `removed` int(11) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_group_member-group` (`group_id`),
  KEY `FK_group_members-user` (`user_id`),
  CONSTRAINT `FK_group_member-group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `FK_group_members-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message_type_id` int(10) unsigned NOT NULL,
  `message_header` varchar(255) NOT NULL,
  `message_body` text,
  `message_path` varchar(255) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `message-person` (`user_id`),
  KEY `FK_message-message_type` (`message_type_id`),
  CONSTRAINT `FK_message-message_type` FOREIGN KEY (`message_type_id`) REFERENCES `message_types` (`id`),
  CONSTRAINT `FK_messages-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.message_favorites
CREATE TABLE IF NOT EXISTS `message_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message_id` int(10) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQUE_user_message` (`user_id`,`message_id`),
  KEY `FK_message_favorites-user` (`user_id`),
  KEY `FK_message_favorites-message` (`message_id`),
  CONSTRAINT `FK_message_favorites-message` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`),
  CONSTRAINT `FK_message_favorites-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.message_recipients
CREATE TABLE IF NOT EXISTS `message_recipients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `facebook_id` varchar(45) DEFAULT NULL,
  `twitter_handle` varchar(45) DEFAULT NULL,
  `read` int(11) DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_message_recipient-message` (`message_id`),
  KEY `FK_message_recipients-user` (`user_id`),
  KEY `FK_message_recipients-group` (`group_id`),
  CONSTRAINT `FK_message_recipient-message` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`),
  CONSTRAINT `FK_message_recipients-group` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `FK_message_recipients-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.message_types
CREATE TABLE IF NOT EXISTS `message_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.people
CREATE TABLE IF NOT EXISTS `people` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `aliases` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(6) NOT NULL,
  `notes` text,
  `twitter_handle` varchar(45) DEFAULT NULL,
  `facebook_id` varchar(45) DEFAULT NULL,
  `email_id` int(10) unsigned DEFAULT NULL,
  `address_id` int(10) unsigned DEFAULT NULL,
  `phone_id` int(10) unsigned DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_facebook_id` (`facebook_id`),
  KEY `FK_people-emails` (`email_id`),
  KEY `FK_people-addresses` (`address_id`),
  KEY `FK_people-phones` (`phone_id`),
  CONSTRAINT `FK_people-addresses` FOREIGN KEY (`address_id`) REFERENCES `person_addresses` (`id`),
  CONSTRAINT `FK_people-emails` FOREIGN KEY (`email_id`) REFERENCES `person_emails` (`id`),
  CONSTRAINT `FK_people-phones` FOREIGN KEY (`phone_id`) REFERENCES `person_phones` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.person_addresses
CREATE TABLE IF NOT EXISTS `person_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `address_type` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `postal_code` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_person_addresses-user` (`person_id`),
  CONSTRAINT `FK_person_addresses-people` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.person_emails
CREATE TABLE IF NOT EXISTS `person_emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `email_type` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_person_emails-people` (`person_id`),
  CONSTRAINT `FK_person_emails-people` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.person_phones
CREATE TABLE IF NOT EXISTS `person_phones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `phone_type` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_person_phones-people` (`person_id`),
  CONSTRAINT `FK_person_phones-people` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.preferences
CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_preferences-users` (`user_id`),
  CONSTRAINT `FK_preferences-users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ios_product_id` varchar(1024) NOT NULL,
  `android_product_id` varchar(1024) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.products_users
CREATE TABLE IF NOT EXISTS `products_users` (
  `user_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`product_id`),
  KEY `FK_products_users-users` (`user_id`),
  KEY `FK_products_users-products` (`product_id`),
  CONSTRAINT `FK_products_users-products` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_products_users-users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for procedure tango.returnMessageThread
DELIMITER //
CREATE DEFINER=`tango`@`%` PROCEDURE `returnMessageThread`(IN `paramUserId` INT(10), IN `paramDate` varchar(70))
BEGIN 

SELECT
				CONVERT(IF(m.user_id=paramUserId,CONCAT(m.user_id,"-",mr.user_id),CONCAT(mr.user_id,"-",m.user_id)),CHAR) AS thread_id,
				IF(m.user_id=paramUserId,mr.user_id,m.user_id) AS friend_id,
				0 as group_id,
				MAX(m.create_date) as create_date,
				SUM(IF(mr.`read`=0 AND mr.user_id = paramUserId ,1,0)) AS unread,
            COUNT(mr.user_id) as thread_total_number
			FROM messages m
			INNER JOIN message_recipients mr
				ON mr.message_id=m.id
				AND mr.group_id IS NULL
			WHERE ((mr.user_id = paramUserId AND m.user_id != paramUserId AND  m.user_id NOT IN (SELECT blocked_user_id FROM blocks WHERE(user_id = paramUserId AND blocked = 1))) 
				OR (m.user_id = paramUserId AND mr.user_id != paramUserId AND  mr.user_id NOT IN (SELECT blocked_user_id FROM blocks WHERE(user_id = paramUserId AND blocked = 1))))
				AND m.delete_date IS NULL
				AND m.create_date >= paramDate  
			GROUP BY thread_id
			
			UNION

			SELECT
			    CONCAT(paramUserId,"-",mr.group_id) thread_id,
			    0 AS friend_id,
			    mr.group_id,
			    MAX(m.create_date) as create_date,
			    SUM( IF(mr.`read`=0 AND mr.user_id = paramUserId, 1, 0)) AS unread,
                COUNT(mr.group_id) as thread_total_number
			FROM messages m
			INNER JOIN message_recipients mr
			    ON mr.message_id=m.id
				AND mr.user_id = paramUserId
			WHERE  mr.group_id IN (SELECT DISTINCT group_id FROM group_members WHERE user_id = paramUserId)
                AND  mr.group_id NOT IN (SELECT group_id FROM group_blocks WHERE(user_id = paramUserId AND blocked = 1))
                AND  mr.group_id NOT IN (SELECT id FROM groups WHERE delete_date IS NOT NULL)
			    AND m.delete_date IS NULL
				AND m.create_date >= paramDate
			GROUP BY thread_id
			ORDER BY create_date DESC; END//
DELIMITER ;


-- Dumping structure for table tango.social_messages
CREATE TABLE IF NOT EXISTS `social_messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `message_type` varchar(15) NOT NULL,
  `message_body` varchar(300) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_social_messages_user` (`user_id`) USING BTREE,
  CONSTRAINT `FK_social_messages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `person_id` int(10) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `facebook_access_token` varchar(255) DEFAULT NULL,
  `twitter_auth_token` varchar(255) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_users-people` (`person_id`),
  CONSTRAINT `FK_users-people` FOREIGN KEY (`person_id`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.user_person_addresses
CREATE TABLE IF NOT EXISTS `user_person_addresses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `person_address_id` int(10) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_person_addresses-user` (`user_id`),
  KEY `FK_user_person_addresses-person_address` (`person_address_id`),
  CONSTRAINT `FK_user_person_addresses-person_address` FOREIGN KEY (`person_address_id`) REFERENCES `person_addresses` (`id`),
  CONSTRAINT `FK_user_person_addresses-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.user_person_emails
CREATE TABLE IF NOT EXISTS `user_person_emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `person_email_id` int(10) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_person_emails-user` (`user_id`),
  KEY `FK_user_person_emails-person_emails` (`person_email_id`),
  CONSTRAINT `FK_user_person_emails-person_emails` FOREIGN KEY (`person_email_id`) REFERENCES `person_emails` (`id`),
  CONSTRAINT `FK_user_person_emails-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.


-- Dumping structure for table tango.user_person_phones
CREATE TABLE IF NOT EXISTS `user_person_phones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `person_phone_id` int(10) unsigned NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `delete_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_person_phones-user` (`user_id`),
  KEY `FK_user_person_phones-person_phones` (`person_phone_id`),
  CONSTRAINT `FK_user_person_phones-person_phones` FOREIGN KEY (`person_phone_id`) REFERENCES `person_phones` (`id`),
  CONSTRAINT `FK_user_person_phones-user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
