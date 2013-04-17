CREATE view segment_vw as
SELECT 
s1.id AS id, 
s1.parent_id AS parent_id,
s1.appl_dict_id AS appl_dict_id,
d1.code AS appl_code,
s1.code AS code,
s2.code AS parent_code,
s1.description AS description,
s1.rank_n AS rank_n,
s1.type_dict_id AS type_dict_id,
d2.code AS type_code,
s1.prod_f AS prod_f,
s1.supported_f AS supported_f,
s1.default_f AS default_f,
u.code AS last_user_code,
s1.last_modif_d AS last_modif_d
FROM segment s1, user u
LEFT JOIN segment s2 ON s1.parent_id = s2.id
LEFT JOIN dict d1 ON s1.appl_dict_id = d1.dict_id
LEFT JOIN dict d2 ON s1.type_dict_id = d2.dict_id
WHERE u.id = s1.last_user_id;
