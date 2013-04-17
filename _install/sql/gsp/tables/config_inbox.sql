CREATE TABLE config_inbox
(
id                         INTEGER NOT NULL PRIMARY KEY,
inbox_code                 VARCHAR(20) NOT NULL,
inbox_description          VARCHAR(40),
inbox_db_path              VARCHAR(40),
inbox_db_table             VARCHAR(20),
automatic_f                TINYINT NOT NULL DEFAULT '1',
mailbox_readonly_f         TINYINT NOT NULL,
mailbox_server             VARCHAR(30),
mailbox_server_protocol_id INTEGER NOT NULL,
mailbox_server_port        TINYINT,
mailbox_treated_folder     VARCHAR(20),
user_code                  VARCHAR(30),
password                   VARCHAR(40),
active_f                   TINYINT NOT NULL DEFAULT '1',
default_f                  TINYINT NOT NULL,
last_user_id               INTEGER,
last_modif_d               DATETIME
);
