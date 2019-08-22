


SELECT cwc.id as application_id,
        toc.*,
        paf.*,
        cwc.*
FROM
(SELECT a.id, a.createdate,  o_t.name as optintype_name ,  m_t.name as messagetype_name, s_t.name as statustype_name, if(s_t.id ='1', '1', '0' ) as success  , if(o_t.name='cwc',a_m.return_id,null) as cwc_id
FROM application a,
    application_optin a_o,
    optintype o_t,
    application_messagestatus a_m,
    messagetype m_t,
    statustype s_t
WHERE a.id = a_o.application_id
    AND a_o.optintype_id = o_t.id
    AND a_o.id = a_m.optin_id
    AND m_t.id = a_m.messagetype_id
    AND s_t.id = a_m.statustype_id
    AND a.createdate > DATE_ADD(CURDATE(), INTERVAL -10 DAY) AND a.createdate < CURDATE()
    AND o_t.name = 'toc'
ORDER BY a.id) as toc,

(SELECT a.id,a.createdate,  o_t.name as optintype_name ,  m_t.name as messagetype_name, s_t.name as statustype_name, if(s_t.id ='1', '1', '0' ) as success  , if(o_t.name='cwc',a_m.return_id,null) as cwc_id
FROM application a,
    application_optin a_o,
    optintype o_t,
    application_messagestatus a_m,
    messagetype m_t,
    statustype s_t
WHERE a.id = a_o.application_id
    AND a_o.optintype_id = o_t.id
    AND a_o.id = a_m.optin_id
    AND m_t.id = a_m.messagetype_id
    AND s_t.id = a_m.statustype_id
    AND a.createdate > DATE_ADD(CURDATE(), INTERVAL -10 DAY) AND a.createdate < CURDATE()
    AND o_t.name = 'a360'
ORDER BY a.id) as paf,

(SELECT a.id,a.createdate,  o_t.name as optintype_name ,  m_t.name as messagetype_name, s_t.name as statustype_name, if(s_t.id ='1', '1', '0' ) as success  , if(o_t.name='cwc',a_m.return_id,null) as cwc_id
FROM application a,
    application_optin a_o,
    optintype o_t,
    application_messagestatus a_m,
    messagetype m_t,
    statustype s_t
WHERE a.id = a_o.application_id
    AND a_o.optintype_id = o_t.id
    AND a_o.id = a_m.optin_id
    AND m_t.id = a_m.messagetype_id
    AND s_t.id = a_m.statustype_id
    AND a.createdate > DATE_ADD(CURDATE(), INTERVAL -10 DAY) AND a.createdate < CURDATE()
    AND o_t.name = 'cwc'
ORDER BY a.id) as cwc


GROUP BY application_id



