CREATE view event_vw as
SELECT
e.id AS id,
e.parent_id AS parent_id,
e.project_id AS project_id,
e.type_dict_id AS type_dict_id,
d5.code AS type_code,
e.code AS code,
(select code from event_ext_code_vw where main_f = 1 and event_id = e.id) AS main_ext_code,
e.summary AS summary,
e.owner_id AS owner_id,
u2.contact_code AS owner,
u2.code AS owner_usercode,
d1.dict_id AS status_dict_id,
d1.code AS status_code,
( select cast(round(julianday(datetime('now','localtime')) - julianday (MAX(date_d))) as integer)
  FROM event_history WHERE event_id = e.id and 
  type_dict_id IN(57,41,43,40)
) AS last_modif_status_d,
d2.code AS state,
e.priority_dict_id AS priority_dict_id,
d3.code AS priority_code,
d3.rank_n AS priority_rank,
e.severity_dict_id AS severity_dict_id,
d4.code AS severity_code,
e.contact_id AS contact_id,
c1.code AS contact,
c1.name AS contact_name,
e.appl_dict_id AS appl_dict_id,
d6.code AS appl_code,
e.package_id AS package_id,
p.code AS package_code,
e.segment_id AS segment_id,
s.code AS segment_code,
e.impact_dict_id AS impact_dict_id,
d7.code AS impact_code,
e.prod_f AS prod_f,
e.blocking_f AS blocking_f,
e.followed_f AS followed_f,
e.planif_package_id AS planif_package_id,
d8.code AS planif_package_code,
e.asked_d AS asked_d,
e.planif_d AS planif_d,
e.opened_d AS opened_d,
e.logged_d AS logged_d,
e.closed_d AS closed_d,
u1.code AS last_user_code,
e.last_modif_d AS last_modif_d
FROM dict d1, dict d2, event e, package p, segment s, user u1
LEFT JOIN contact c1 ON e.contact_id = c1.id
LEFT JOIN user_vw u2 ON e.owner_id = u2.id
LEFT JOIN dict d3 ON e.priority_dict_id = d3.dict_id
LEFT JOIN dict d4 ON e.severity_dict_id = d4.dict_id
LEFT JOIN dict d5 ON e.type_dict_id = d5.dict_id
LEFT JOIN dict d6 ON e.appl_dict_id = d6.dict_id
LEFT JOIN dict d7 ON e.impact_dict_id = d7.dict_id
LEFT JOIN dict d8 ON e.planif_package_id = d8.dict_id
WHERE d1.dict_id = e.status_dict_id
and d2.dict_id = d1.parent_dict_id
and p.id = e.package_id
and s.id = e.segment_id
and u1.id = e.last_user_id;
