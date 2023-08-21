-- 6. Выбрать без join-ов и подзапросов все департаменты, в которых есть мужчины, и все они (каждый) поставили высокую оценку (строго выше 5).

create table evaluations
(
    respondent_id uuid primary key, -- ID респондента
    department_id uuid,             -- ID департамента
    gender        boolean,          -- true — мужчина, false — женщина 
    value         integer	    -- Оценка
);

SELECT DISTINCT department_id -- Выбираем уникальные ID департаментов
FROM evaluations 
WHERE gender = true -- Оставляем только мужчин 
    AND value > 5 -- Оставляем только оценки выше 5
GROUP BY department_id -- Группируем записи по ID департамента
HAVING COUNT(DISTINCT respondent_id) > 0; -- Оставляем только записи с уникальными респондентами (оценки от каждого мужчины),
                                          -- у которых есть оценка больше 5