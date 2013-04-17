CREATE TABLE dict
(
dict_id        INTEGER NOT NULL PRIMARY KEY,
parent_dict_id INTEGER,
code           VARCHAR(20) NOT NULL,
description    VARCHAR(40),
active_f       TINYINT NOT NULL DEFAULT '1',
rank_n         SMALLINT NOT NULL DEFAULT '1'
);

INSERT into dict values ('1', NULL, 'status', 'Statut',1, '1');
INSERT into dict values ('2', NULL, 'type', 'Type',1, '2');
INSERT into dict values ('3', NULL, 'brand', 'Marque',1, '3');
INSERT into dict values ('4', NULL, 'connection', 'Connexion',1, '4');
INSERT into dict values ('11', '1', 'stock', 'En Stock',1, '1');
INSERT into dict values ('12', '1', 'defect', 'Défectueux',1, '2');
INSERT into dict values ('13', '1', 'repair', 'En réparation',1, '3');
INSERT into dict values ('14', '1', 'prod', 'En production',1, '4');
INSERT into dict values ('21', '2', 'pc', 'PC',1, '1');
INSERT into dict values ('22', '2', 'screen', 'Ecran',1, '2');
INSERT into dict values ('23', '2', 'phone', 'Téléphone',1, '3');
INSERT into dict values ('24', '2', 'headphone', 'Casque',1, '4');
INSERT into dict values ('25', '2', 'switch', 'Switch',1, '5');
INSERT into dict values ('31', '3', 'hp', 'HP',1, '1');
INSERT into dict values ('32', '3', 'dell', 'Dell',1, '2');
INSERT into dict values ('33', '3', 'sun', 'Sun',1, '3');
INSERT into dict values ('34', '3', 'sony', 'Sony',1, '4');
INSERT into dict values ('35', '3', 'cisco', 'Cisco',1, '5');
INSERT into dict values ('41', '4', 'auto', 'Automatique',1, '1');
INSERT into dict values ('42', '4', 'half_10', '10 Half',1, '2');
INSERT into dict values ('43', '4', 'half_100', '100 Half',1, '3');
INSERT into dict values ('44', '4', 'half_1000', '1000 Half',1, '4');
INSERT into dict values ('45', '4', 'full_10', '10 Full',1, '5');
INSERT into dict values ('46', '4', 'full_100', '100 Full',1, '6');
INSERT into dict values ('47', '4', 'full_1000', '1000 Full',1, '7');

CREATE view dict_vw as
SELECT
d1.dict_id as dict_id,
d1.parent_dict_id as parent_dict_id,
d1.code as code,
d2.code as parent_code,
d2.description as parent_description,
d1.description as description,
d1.active_f as active_f,
d1.rank_n as rank_n
FROM dict d1
LEFT JOIN dict d2 ON d1.parent_dict_id = d2.dict_id
ORDER by d1.parent_dict_id, d1.rank_n, d1.dict_id;

CREATE TABLE network
(
id			INTEGER NOT NULL PRIMARY KEY,
hardware_id		INTEGER,
port_n			INTEGER,
connection_dict_id	INTEGER
);

CREATE TABLE hardware
(
id               	INTEGER NOT NULL PRIMARY KEY,
description       	VARCHAR(20),
status_dict_id         	INTEGER,
hostname          	VARCHAR(20),
ip_address		VARCHAR(20),
internal_reference   	VARCHAR(20),
serial_n		VARCHAR[20],
brand_dict_id           VARCHAR[20],
model			VARCHAR[20],
type_dict_id		INTEGER,
location		VARCHAR[20],
acquisition_d		DATE,
install_d		DATE,
network_id		INTEGER,
contact_id		INTEGER
);

CREATE view hardware_vw as
SELECT
h.id as id,
h.description as description,
h.status_dict_id as status_dict_id,
d1.code as status_code,
d1.description as status_description,
h.hostname as hostname,
h.internal_reference as internal_reference,
h.serial_n as serial_n,
h.brand_dict_id as brand_dict_id,
d2.code as brand_code,
d2.description as brand_description,
h.model as model,
h.type_dict_id as type_dict_id,
d3.code as type_code,
d3.description as type_description,
h.location as location,
h.acquisition_d as acquisition_d,
h.install_d as install_d,
h.network_id as network_id,
h.contact_id as contact_id
FROM dict d1, dict d2, dict d3, hardware h
LEFT JOIN network n ON h.network_id = n.id
WHERE d1.dict_id = h.status_dict_id
and d2.dict_id = h.brand_dict_id
and d3.dict_id = h.type_dict_id;

CREATE view network_vw as
SELECT
n.id as id,
h.id as hardware_id,
h.description as hardware_description,
d1.code as hardware_type,
n.port_n as port_n,
n.connection_dict_id as connection_dict_id,
d2.description as connection_description
FROM hardware h, network n, dict d1, dict d2
where h.id = n.hardware_id
and d1.dict_id = h.type_dict_id
and d2.dict_id = n.connection_dict_id;

CREATE table event_hardware
(
event_id                INTEGER,
hardware_id             INTEGER
);

CREATE UNIQUE INDEX idx_uni_dict ON dict(code,parent_dict_id);
CREATE UNIQUE INDEX idx_uni_network ON network(hardware_id, port_n, connection_dict_id);
CREATE UNIQUE INDEX idx_uni_hardware ON hardware(description);
CREATE UNIQUE INDEX idx_uni_event_hardware ON event_hardware(event_id, hardware_id);

CREATE TRIGGER trg_upd_dict BEFORE UPDATE ON dict FOR EACH ROW
BEGIN
	SELECT RAISE(ROLLBACK, 'Cannot delete : switch type code cannot be modified')
        WHERE (SELECT dict_id FROM dict WHERE code='switch' and code != NEW.code and dict_id = OLD.dict_id) IS NOT NULL;
END;

CREATE TRIGGER trg_del_dict BEFORE DELETE ON dict FOR EACH ROW
BEGIN
        SELECT RAISE(ROLLBACK, 'Cannot delete : connection is referenced in table network')
        WHERE (SELECT id FROM network WHERE connection_dict_id = OLD.dict_id) IS NOT NULL;

        SELECT RAISE(ROLLBACK, 'Cannot delete : status is referenced in table hardware')
        WHERE (SELECT id FROM hardware WHERE status_dict_id = OLD.dict_id) IS NOT NULL;

        SELECT RAISE(ROLLBACK, 'Cannot delete : brand is referenced in table hardware')
        WHERE (SELECT id FROM hardware WHERE brand_dict_id = OLD.dict_id) IS NOT NULL;

        SELECT RAISE(ROLLBACK, 'Cannot delete : type is referenced in table hardware')
        WHERE (SELECT id FROM hardware WHERE type_dict_id = OLD.dict_id) IS NOT NULL;

        SELECT RAISE(ROLLBACK, 'Cannot delete : switch type cannot be deleted')
        WHERE (SELECT dict_id FROM dict WHERE code='switch' and dict_id = OLD.dict_id) IS NOT NULL;

        SELECT RAISE(ROLLBACK, 'Cannot delete : this attribute is not empty')
        WHERE (SELECT dict_id FROM dict WHERE parent_dict_id = OLD.dict_id) IS NOT NULL;
END;

CREATE TRIGGER trg_del_network BEFORE DELETE ON network FOR EACH ROW
BEGIN
        SELECT RAISE(ROLLBACK, 'Cannot delete : network is referenced in table hardware')
        WHERE (SELECT id FROM hardware WHERE network_id = OLD.id) IS NOT NULL;
END;

CREATE TRIGGER trg_del_hardware BEFORE DELETE ON hardware FOR EACH ROW
BEGIN
        SELECT RAISE(ROLLBACK, 'Cannot delete : hardware is referenced in table network')
        WHERE (SELECT id FROM network WHERE hardware_id = OLD.id) IS NOT NULL;

        SELECT RAISE(ROLLBACK, 'Cannot delete : hardware is referenced in table event_hardware')
        WHERE (SELECT event_id FROM event_hardware WHERE hardware_id = OLD.id) IS NOT NULL;
END;
