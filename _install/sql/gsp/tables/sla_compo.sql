CREATE TABLE sla_compo
(
id               INTEGER NOT NULL PRIMARY KEY,
sla_id           INTEGER NOT NULL,
type_dict_id     INTEGER NOT NULL,
severity_dict_id INTEGER NOT NULL,
step_dict_id     INTEGER NOT NULL,
max_time_h       INTEGER,
last_user_id     INTEGER,
last_modif_d     DATETIME
);
