CREATE view event_history_vw as
SELECT
eh.id as id,
eh.event_id as event_id,
eh.type_dict_id as type_id,
d1.code as type_code,
eh.date_d as date,
eh.status_dict_id as status_id,
d2.code as status_code,
eh.priority_dict_id as priority_id,
d3.code as priority_code,
eh.mail_from as mail_from,
eh.mail_to as mail_to,
eh.mail_cc as mail_cc,
CASE WHEN eh.description is null and eh.type_dict_id = (select dict_id from dict_vw where code = 'statut' and parent_code = 'historique')
THEN 
	(select description from dict_vw where code = 'statut' and parent_code = 'historique') || ' -> ' || d2.code
	 
ELSE
	CASE WHEN eh.description is null and eh.type_dict_id = (select dict_id from dict_vw where code = 'priority' and parent_code = 'historique')
	THEN 
		(select description from dict_vw where code = 'priority' and parent_code = 'historique') || ' -> ' || d3.code
	ELSE 
		eh.description 
	END
END as description,
eh.contact_id as contact_id,
c.code as contact_code,
u.code as last_user_code,
eh.last_modif_d as last_modif_d
FROM dict d1, event_history eh
LEFT JOIN contact c ON eh.contact_id = c.id
LEFT JOIN user u ON eh.last_user_id = u.id
LEFT JOIN dict d2 ON eh.status_dict_id = d2.dict_id
LEFT JOIN dict d3 ON eh.priority_dict_id = d3.dict_id
WHERE d1.dict_id = eh.type_dict_id;
