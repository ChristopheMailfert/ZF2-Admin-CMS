CREATE TABLE admin_user
(
    user_id       INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
    firstname     VARCHAR(50) DEFAULT NULL,
    lastname      VARCHAR(50) DEFAULT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    username      VARCHAR(255) DEFAULT NULL UNIQUE,
    password      VARCHAR(128) NOT NULL,
    created       DATETIME NOT NULL,
    modified      DATETIME DEFAULT NULL,
    logdate       DATETIME DEFAULT NULL,
    is_active     TINYINT(1) NOT NULL
) ENGINE=InnoDB;