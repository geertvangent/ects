UPDATE chamilo.atlantis_context AS a
        JOIN
    chamilo.atlantis_context AS b ON b.context_id = a.parent_id
        AND b.context_type = a.parent_type 
SET 
    a.parent_id = b.id;