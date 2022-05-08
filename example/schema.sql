-- Users

CREATE TABLE IF NOT EXISTS `users`
(
    `id` BIGINT AUTO_INCREMENT NOT NULL,
    `email` VARCHAR(256),
    `password` VARCHAR(256),
    PRIMARY KEY(`id`),
    UNIQUE KEY(`email`)
);

-- Posts

CREATE TABLE IF NOT EXISTS `posts`
(
  `id` BIGINT AUTO_INCREMENT NOT NULL,
  `title` VARCHAR(256),
  `content` TEXT,
  PRIMARY KEY(`id`)
);
