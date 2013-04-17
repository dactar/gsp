CREATE view dict_vw as
SELECT
d1.dict_id as dict_id,
d1.parent_dict_id as parent_dict_id,
d1.code as code,
d2.code as parent_code,
d1.description as description,
d1.parent_f as parent_f,
d1.mandatory_f as mandatory_f,
d1.active_f as active_f,
d1.rank_n as rank_n,
u.code as last_user_code,
d1.last_modif_d as last_modif_d
FROM dict d1
LEFT JOIN dict d2 ON d1.parent_dict_id = d2.dict_id, user u
WHERE u.id = d1.last_user_id
ORDER by d1.parent_dict_id, d1.rank_n, d1.dict_id;
