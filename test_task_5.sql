-- 5. В базе данных имеется таблица с товарами goods (id INTEGER, name TEXT), 
-- таблица с тегами tags (id INTEGER, name TEXT) и 
-- таблица связи товаров и тегов goods_tags (tag_id INTEGER, goods_id INTEGER, UNIQUE(tag_id, goods_id)). 
-- Выведите id и названия всех товаров, которые имеют все возможные теги в этой базе.

SELECT g.id, g.name -- Выбираем идентификатор (id) и название (name) всех товаров
FROM goods AS g -- Присоединяем таблицу связи товаров и тегов
JOIN goods_tags AS gt ON g.id = gt.goods_id
JOIN tags AS t ON gt.tag_id = t.id -- Присоединяем таблицу тегов
GROUP BY g.id, g.name -- Группируем результаты по идентификатору (id) и названию (name) товаров
HAVING COUNT(DISTINCT gt.tag_id) = (SELECT COUNT(*) FROM tags); -- Фильтруем только те товары, которые имеют количество уникальных тегов, равное количеству всех возможных тегов в базе данных