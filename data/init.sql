CREATE DATABASE userList;
USE userList;
CREATE TABLE users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    psw VARCHAR(255) NOT NULL,
    date TIMESTAMP
);

