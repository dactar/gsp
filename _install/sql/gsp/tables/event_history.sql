CREATE TABLE event_history
(
id               INTEGER NOT NULL PRIMARY KEY,
event_id         INTEGER NOT NULL,
type_dict_id     INTEGER,
date_d           DATETIME,
status_dict_id   INTEGER,
priority_dict_id INTEGER,
mail_from        VARCHAR(40),
mail_to          VARCHAR(1023),
mail_cc          VARCHAR(1023),
description      TEXT,
contact_id       INTEGER,
self_sent_f      TINYINT NOT NULL DEFAULT '0',
last_user_id     INTEGER,
last_modif_d     DATETIME
);
