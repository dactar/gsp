CREATE TABLE filter_compo
(
id              INTEGER NOT NULL PRIMARY KEY,
description     VARCHAR(40),
filter_id       INTEGER,
search_pattern  VARCHAR(80),
replace_pattern VARCHAR(40),
rank_n          SMALLINT,
last_user_id    INTEGER,
last_modif_d    DATETIME
);
