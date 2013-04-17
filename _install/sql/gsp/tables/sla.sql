CREATE TABLE sla
(
id                       INTEGER NOT NULL PRIMARY KEY,
code                     VARCHAR(10),
name                     VARCHAR(40),
organisation_dict_id     INTEGER NOT NULL,
global_calc_rule_dict_id INTEGER NOT NULL,
rank_n                   SMALLINT NOT NULL DEFAULT '1',
active_f                 TINYINT NOT NULL DEFAULT '1',
last_user_id             INTEGER,
last_modif_d             DATETIME
);
