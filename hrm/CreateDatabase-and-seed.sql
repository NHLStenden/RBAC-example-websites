DROP DATABASE IF EXISTS HRM;
CREATE DATABASE HRM;
USE HRM;

DROP USER IF EXISTS 'admin'@'%';
CREATE USER 'admin'@'%' IDENTIFIED WITH mysql_native_password AS PASSWORD('Test1234!');

GRANT ALL ON HRM.* TO 'admin'@'%';

CREATE TABLE medewerkers (
    idMedewerker INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    personeelsnummer INT UNIQUE ,
    voornaam VARCHAR(80) NOT NULL,
    achternaam VARCHAR(80) NOT NULL,
    team VARCHAR(80) NOT NULL,
    functie VARCHAR(80) NOT NULL,
    telefoonnummer VARCHAR(16) NULL,
    kamernummer VARCHAR(16) NULL,
    medewerkerType VARCHAR(15) NULL,
    postcode VARCHAR(12) NULL,
    last_sync DATETIME NULL
) COMMENT 'Medewerkers NHL Stenden';

