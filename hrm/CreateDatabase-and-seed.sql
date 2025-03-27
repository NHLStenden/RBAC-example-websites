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
    postcode VARCHAR(12) NULL
) COMMENT 'Medewerkers NHL Stenden';

/*
LOAD DATA INFILE '/docker-entrypoint-initdb.d/Marketing-staff.csv'

    INTO TABLE medewerkers
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    IGNORE 1 ROWS
    (achternaam, personeelsnummer, medewerkerType, voornaam, @dummy1, postcode, kamernummer, telefoonnummer, @uid)
    SET team = 'NHL Stenden', functie = 'medewerker marketing';

LOAD DATA INFILE '/docker-entrypoint-initdb.d/ICT-staff.csv'
    INTO TABLE medewerkers
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    IGNORE 1 ROWS
    (achternaam, personeelsnummer, medewerkerType, voornaam, @dummy1, postcode, kamernummer, telefoonnummer, @uid)
    SET team = 'NHL Stenden', functie = 'medewerker ICT';

LOAD DATA INFILE '/docker-entrypoint-initdb.d/teachers.csv'
    INTO TABLE medewerkers
    FIELDS TERMINATED BY ','
    OPTIONALLY ENCLOSED BY '"'
    IGNORE 1 ROWS
    (achternaam, personeelsnummer, medewerkerType, voornaam, @dummy1, postcode, kamernummer, telefoonnummer, @uid)
    SET team = 'NHL Stenden', functie = 'docent';

*/