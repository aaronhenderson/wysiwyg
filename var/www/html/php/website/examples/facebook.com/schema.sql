CREATE TABLE `user_account` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_handle` varchar(45) NOT NULL,
  `user_email` varchar(1125) NOT NULL,
  `user_password_hash` varchar(255) NOT NULL,
  `user_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `user_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `user_created_from` varchar(45) DEFAULT NULL,
  `user_created_timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_id_UNIQUE` (`user_id`),
  UNIQUE KEY `user_handle_UNIQUE` (`user_handle`),
  UNIQUE KEY `user_email_UNIQUE` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

CREATE TABLE `user_messages` (
  `user_messages_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_messages_message` text NOT NULL,
  `user_messages_recipient_id` int(11) NOT NULL,
  `user_messages_sender_id` int(11) NOT NULL,
  `user_messages_sender_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_messages_sent_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_messages_read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_messages_id`),
  UNIQUE KEY `user_messages_id_UNIQUE` (`user_messages_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_profile` (
  `user_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_firstname` varchar(65) NOT NULL DEFAULT '',
  `user_lastname` varchar(85) NOT NULL DEFAULT '',
  `user_biography` longtext NOT NULL,
  `user_picture` varchar(125) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_profile_id`),
  UNIQUE KEY `user_uid_UNIQUE` (`user_id`),
  UNIQUE KEY `user_profile_id_UNIQUE` (`user_profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_timeline` (
  `user_timeline_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_timeline_user_id` int(11) NOT NULL,
  `user_timeline_author_id` int(11) NOT NULL,
  `user_timeline_message_body` text NOT NULL,
  `user_timeline_posted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_timeline_posted_from` varchar(45) NOT NULL DEFAULT '127.0.0.1',
  `user_timeline_deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`user_timeline_id`),
  UNIQUE KEY `user_timeline_id_UNIQUE` (`user_timeline_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_token` (
  `user_token_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_token_user_id` int(11) NOT NULL,
  `user_token_value` varchar(105) NOT NULL DEFAULT '',
  `user_token_ip_address` varchar(45) NOT NULL DEFAULT '127.0.0.1',
  `user_token_expires` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_token_id`),
  UNIQUE KEY `user_recovery_token_id_UNIQUE` (`user_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
