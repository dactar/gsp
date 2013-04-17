CREATE TABLE dict
(
dict_id        INTEGER NOT NULL PRIMARY KEY,
parent_dict_id INTEGER,
code           VARCHAR(20) NOT NULL,
description    VARCHAR(40),
parent_f       TINYINT NOT NULL DEFAULT '0',
mandatory_f    TINYINT NOT NULL DEFAULT '0',
active_f       TINYINT NOT NULL DEFAULT '1',
rank_n         SMALLINT NOT NULL DEFAULT '1',
last_user_id   INTEGER,
last_modif_d   DATETIME,
CHECK          (parent_dict_id != dict_id)
);
