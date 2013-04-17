CREATE TABLE segment
(
id           INTEGER NOT NULL PRIMARY KEY,
parent_id    INTEGER,
appl_dict_id INTEGER,
code         VARCHAR(20),
description  VARCHAR(40),
rank_n       SMALLINT NOT NULL DEFAULT '1',
type_dict_id INTEGER,
prod_f       TINYINT NOT NULL,
default_f    TINYINT NOT NULL DEFAULT '0',
supported_f  TINYINT NOT NULL DEFAULT '1',
last_user_id INTEGER,
last_modif_d DATETIME,
CHECK        (parent_id != id)
);
