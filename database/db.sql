

  CREATE TABLE token (
    `id` VARCHAR(20) NOT NULL,
    `validity` INT(20) NOT NULL,
    `user` VARCHAR(50) NOT NULL,
    `logTime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) 


  CREATE TABLE user (
    `user_id` VARCHAR(50) NOT NULL,
    `user_passwd` VARCHAR(64) NOT NULL,
    `user_fname` VARCHAR(50) NOT NULL,
    `user_lname` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`user_id`)
  ) 

  CREATE TABLE archive (
  	`id` INT NOT NULL AUTO_INCREMENT, 
  	`worked` TIMESTAMP NOT NULL, 
  	`user` VARCHAR(50) NOT NULL, 
  	PRIMARY KEY (`id`)
  ) 











