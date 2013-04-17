CREATE TABLE contact
(
id                 INTEGER NOT NULL PRIMARY KEY,
code               VARCHAR(10) NOT NULL,
name               VARCHAR(30),
language_dict_id   INTEGER,
active_f           TINYINT NOT NULL DEFAULT '1',
phone              VARCHAR(20),
mobile             VARCHAR(20),
email              VARCHAR(30),
url                VARCHAR(255) DEFAULT 'null',
group_dict_id      INTEGER NOT NULL,
last_user_id       INTEGER,
last_modif_d       DATETIME
);
