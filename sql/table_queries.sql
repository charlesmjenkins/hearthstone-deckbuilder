CREATE TABLE `user` (
  uid int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  username varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  PRIMARY KEY (uid)
) ENGINE=InnoDB;

CREATE TABLE class (
  clid int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (clid)
) ENGINE=InnoDB;

CREATE TABLE card (
  caid varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `type` varchar(20) NOT NULL,
  rarity varchar(15) DEFAULT NULL,
  cost tinyint(3) UNSIGNED DEFAULT NULL,
  attack tinyint(3) UNSIGNED DEFAULT NULL,
  health tinyint(3) UNSIGNED DEFAULT NULL,
  durability tinyint(3) UNSIGNED DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  collectible tinyint(1) DEFAULT NULL,
  elite tinyint(1) DEFAULT NULL,
  class int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (caid),
  FOREIGN KEY (class)
    REFERENCES class(clid)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE deck (
  did int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  class int(11) UNSIGNED NOT NULL,
  `user` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (did),
  FOREIGN KEY (class)
    REFERENCES class(clid)
    ON DELETE CASCADE,
  FOREIGN KEY (`user`)
    REFERENCES `user`(uid)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE deck_card (
  dc_instance int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  did int(11) UNSIGNED NOT NULL,
  caid varchar(20) NOT NULL,
  PRIMARY KEY (dc_instance),
  FOREIGN KEY (did)
    REFERENCES deck(did)
    ON DELETE CASCADE,
  FOREIGN KEY (caid)
    REFERENCES card(caid)
    ON DELETE CASCADE
) ENGINE=InnoDB;