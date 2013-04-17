CREATE TABLE appl_label
(
id                    INTEGER NOT NULL PRIMARY KEY,
entity_dict_id        INTEGER NOT NULL,
object_id             INTEGER NOT NULL,
language_dict_id      INTEGER NOT NULL,
label                 VARCHAR(80),
last_user_id          INTEGER,
last_modif_d          DATETIME
);
