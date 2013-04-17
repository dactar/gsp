CREATE view contact_vw as
SELECT 
c.id AS id, 
c.code AS code,
c.name AS name,
c.language_dict_id AS language_dict_id,
d3.code AS language_code,
c.active_f AS active_f,
c.phone AS phone,
c.mobile AS mobile,
c.email AS email,
c.url AS url,
c.group_dict_id AS group_dict_id,
d1.code AS group_code,
d2.code AS organisation_code,
u.code  AS last_user_code,
c.last_modif_d AS last_modif_d
FROM contact c, dict d1, dict d2, user u
LEFT JOIN dict d3 ON d3.dict_id = c.language_dict_id
WHERE u.id = c.last_user_id
and d1.dict_id = c.group_dict_id
and d2.dict_id = d1.parent_dict_id;
