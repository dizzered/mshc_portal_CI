INSERT INTO legal_attys_users (legal_atty_id, user_id) 

(

SELECT la.id, lfu.user_id FROM legal_firms_users AS lfu

LEFT JOIN legal_attys AS la ON la.legal_firm_id = lfu.legal_firm_id

LEFT JOIN legal_attys_users AS lau ON lau.legal_atty_id = la.id AND lau.user_id = lfu.user_id

WHERE lfu.all_attorneys = 1 AND lau.user_id IS NULL

) ;