CREATE TABLE `user` (
  `user_id` int(11) NOT NULL ,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(30) NOT NULL,
  `user_date_of_birth` date DEFAULT NULL,
  `user_filename_profile` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email_user_u` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci 

CREATE TABLE `blog` (
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `blog_title` varchar(255) DEFAULT NULL,
  `blog_text` text NOT NULL,
  `blog_filename_image` varchar(255) DEFAULT NULL,
  `blog_time` datetime DEFAULT NULL,
  PRIMARY KEY (`blog_id`),
  KEY `user_id_blog_fk` (`user_id`),
  CONSTRAINT `user_id_blog_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_time` datetime DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `blog_id_comment_fk` (`blog_id`),
  KEY `user_id_comment_fk` (`user_id`),
  CONSTRAINT `blog_id_comment_fk` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_id_comment_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `replay` (
  `replay_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `replay_text` text DEFAULT NULL,
  PRIMARY KEY (`replay_id`),
  KEY `user_id_replay_fk` (`user_id`),
  KEY `comment_id_replay_fk` (`comment_id`),
  CONSTRAINT `comment_id_replay_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_id_replay_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

CREATE TABLE `reaction` (
  `user_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `reation_like_count` varchar(15) DEFAULT NULL,
  `reation_laugh_count` varchar(15) DEFAULT NULL,
  `reation_sad_count` varchar(15) DEFAULT NULL,
  `reation_angry_count` varchar(15) DEFAULT NULL,
  KEY `user_id_reaction_fk` (`user_id`),
  KEY `blog_id_reaction_fk` (`blog_id`),
  CONSTRAINT `blog_id_reaction_fk` FOREIGN KEY (`blog_id`) REFERENCES `blog` (`blog_id`)  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_id_reaction_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci