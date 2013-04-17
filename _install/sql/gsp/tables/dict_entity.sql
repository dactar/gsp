CREATE TABLE dict_entity
(
dict_id        INTEGER NOT NULL PRIMARY KEY,
code           VARCHAR(20) NOT NULL,
sqltable       VARCHAR(30) NOT NULL,
last_user_id   INTEGER,
last_modif_d   DATETIME
);
