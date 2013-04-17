CREATE TABLE plugin
(
id                    INTEGER NOT NULL PRIMARY KEY,
code                  VARCHAR(10),
name                  VARCHAR(40),
version               VARCHAR(10),
active_f              TINYINT NOT NULL DEFAULT '0',
installed_f           TINYINT NOT NULL DEFAULT '0'
);
