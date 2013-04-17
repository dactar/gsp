CREATE view func_journal_vw AS
SELECT distinct
'START' AS Type, 
e.logged_d as Date, 
e.code as No, 
e.summary as Description,
u.contact_code as Suivi
FROM event e
LEFT JOIN user_vw u ON e.owner_id = u.id

UNION

SELECT distinct
'END' AS Type, 
e.closed_d as Date, 
e.code as No, 
e.summary as Description,
u.contact_code as Suivi
FROM event e
LEFT JOIN user_vw u ON e.owner_id = u.id
WHERE e.closed_d is not null

UNION

SELECT distinct
'MODIF' AS Type, 
eh.date_d as Date, 
e.code as No, 
e.summary as Description,
u.contact_code as Suivi
FROM event_history eh, event e
LEFT JOIN user_vw u ON e.owner_id = u.id
WHERE e.id = eh.event_id 
and eh.type_dict_id != (select dict_id from dict_vw where code = 'description' and parent_code = 'historique')
and (eh.date_d != e.closed_d or e.closed_d is null)
and eh.date_d > e.logged_d
order by 2 desc;
