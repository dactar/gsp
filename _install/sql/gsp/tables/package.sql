CREATE TABLE package
(
id           INTEGER NOT NULL PRIMARY KEY,
parent_id    INTEGER,
appl_dict_id INTEGER NOT NULL,
code         VARCHAR(20),
description  VARCHAR(40),
rank_n       SMALLINT NOT NULL DEFAULT '1',
type_dict_id INTEGER NOT NULL DEFAULT "0",
prod_f       TINYINT NOT NULL,
planif_d     DATE,
last_user_id INTEGER,
last_modif_d DATETIME,
CHECK        (parent_id != id)
);
