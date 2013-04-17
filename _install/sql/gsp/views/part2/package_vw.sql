CREATE view package_vw as
SELECT 
p1.id AS id, 
p1.parent_id AS parent_id,
p1.appl_dict_id AS appl_dict_id,
d1.code AS appl_code,
p1.code AS code,
p2.code AS parent_code,
p1.description AS description,
p1.rank_n AS rank_n,
p1.type_dict_id AS type_dict_id,
d2.code AS type_code,
p1.prod_f AS prod_f,
p1.planif_d AS planif_d,
u.code AS last_user_code,
p1.last_modif_d AS last_modif_d
FROM package p1, user u
LEFT JOIN package p2 ON p1.parent_id = p2.id
LEFT JOIN dict d1 ON p1.appl_dict_id = d1.dict_id
LEFT JOIN dict d2 ON p1.type_dict_id = d2.dict_id
WHERE u.id = p1.last_user_id;
