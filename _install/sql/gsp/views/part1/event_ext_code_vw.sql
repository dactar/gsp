CREATE view event_ext_code_vw as
SELECT 
x.id AS id, 
x.event_id AS event_id, 
d.code || x.code AS code, 
x.main_f as main_f,
u.code AS last_user_code, 
x.last_modif_d AS last_modif_d
FROM event_ext_code x, dict d, user u
WHERE u.id = x.last_user_id
AND x.type_dict_id = d.dict_id;
