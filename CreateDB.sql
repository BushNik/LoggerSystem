CREATE DATABASE IF NOT EXISTS test_db
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
  USE test_db;
GRANT ALL PRIVILEGES ON test_db.* To 'test'@'localhost' IDENTIFIED BY '123456';
CREATE TABLE IF NOT EXISTS log_table (
    id 			INT NOT NULL AUTO_INCREMENT,
    logtime     TIMESTAMP NOT NULL,
    label       CHAR(16) NOT NULL,
    message     VARCHAR(200),
    PRIMARY KEY (id)
);
