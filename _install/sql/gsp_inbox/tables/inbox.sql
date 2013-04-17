CREATE TABLE inbox
(
id INTEGER   NOT NULL PRIMARY KEY,
creation_d   DATETIME,
config_id    INTEGER NOT NULL,
origin_code  VARCHAR (40) NOT NULL,
mail_date    DATETIME,
mail_from    VARCHAR (40),
mail_to      VARCHAR (1023),
mail_cc      VARCHAR (1023),
mail_subject VARCHAR (80),
mail_size    INTEGER,
complete_f   TINYINT NOT NULL DEFAULT '0',
locked_f     TINYINT NOT NULL DEFAULT '0',
hidden_f     TINYINT NOT NULL DEFAULT '0',
treated_f    TINYINT NOT NULL DEFAULT '0',
mail_body    TEXT,
last_user_id INTEGER,
last_modif_d DATETIME
);
