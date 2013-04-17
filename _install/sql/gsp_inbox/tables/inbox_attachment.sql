CREATE TABLE inbox_attachment
(
id           INTEGER NOT NULL PRIMARY KEY,
mail_id      INTEGER NOT NULL,
rank_n       TINYINT NOT NULL,
type         VARCHAR(10) NOT NULL,
subtype      VARCHAR(10) NOT NULL,
name         VARCHAR(80) ,
data         BLOB ,
last_user_id INTEGER,
last_modif_d DATETIME
);
