CREATE view user_vw as
SELECT 
u1.id AS id, 
u1.code AS code,
u1.name AS name,
(select code from dict where dict_id = c.language_dict_id) AS language_code,
u1.alias AS alias,
c.phone AS phone,
c.email AS email,
u1.active_f AS active_f,
u1.admin_f AS admin_f,
u1.contact_id AS contact_id,
c.code AS contact_code,
u2.code AS last_user_code,
u1.last_modif_d AS last_modif_d
FROM user u1, contact c 
LEFT JOIN user u2 ON u1.last_user_id = u2.id
WHERE c.id = u1.contact_id;
