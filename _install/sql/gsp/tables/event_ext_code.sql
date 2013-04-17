CREATE TABLE event_ext_code
(
id                  INTEGER NOT NULL PRIMARY KEY,
event_id            INTEGER,
code                VARCHAR(20) NOT NULL,
type_dict_id        INTEGER,
main_f              TINYINT NOT NULL DEFAULT '0',
last_user_id        INTEGER,
last_modif_d        DATETIME
);
