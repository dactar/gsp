CREATE TABLE filter
(
id                    INTEGER NOT NULL PRIMARY KEY,
code                  VARCHAR(10),
description           VARCHAR(40),
relation_type_dict_id INTEGER,
relation_dict_id      INTEGER,
limit_n               SMALLINT NOT NULL DEFAULT '-1',
rank_n                SMALLINT,
active_f              TINYINT NOT NULL DEFAULT '1',
last_user_id          INTEGER,
last_modif_d          DATETIME
);
