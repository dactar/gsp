CREATE view sla_vw as
SELECT 
s.id AS id, 
s.code AS code,
s.name AS name,
d1.code AS organisation_code,
d2.code AS global_calc_rule_code,
s.active_f AS active_f,
s.rank_n AS rank_n,
u.code  as last_user_code,
s.last_modif_d AS last_modif_d
FROM sla s, dict d1, dict d2, user u
WHERE u.id = s.last_user_id
and d1.dict_id = s.organisation_dict_id
and d2.dict_id = s.global_calc_rule_dict_id;
