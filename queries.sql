/* Добавляем пользователей */

INSERT INTO users SET user_name = 'Руслан',
                     e_mail = 'rus007@mail.ru',
                     user_password = '$123Wwd';

INSERT INTO users SET user_name = 'Роман',
                     e_mail = 'roman_s@gmail.com',
                     user_password = 'L57kejj7';

/* Далее добавляем проекты для пользователя Роман. Считаем, что это тот пользователь, проекты которого есть у нас в моках*/

INSERT INTO projects SET user_id = 2, project_name = "Все";
INSERT INTO projects SET user_id = 2, project_name = "Входящие";
INSERT INTO projects SET user_id = 2, project_name = "Учеба";
INSERT INTO projects SET user_id = 2, project_name = "Работа";
INSERT INTO projects SET user_id = 2, project_name = "Домашние дела";
INSERT INTO projects SET user_id = 2, project_name = "Авто";

/*Далее добавляем задачи для пользователя Роман*/

INSERT INTO tasks SET user_id = 2,
                     project_id = 4,
                     task_name = 'Собеседование в IT компании',
                     task_deadline = '2020-12-01',
                     task_done = 0

INSERT INTO tasks SET user_id = 2,
                     project_id = 4,
                     task_name = 'Выполнить тестовое задание',
                     task_deadline = '2020-11-29',
                     task_done = 0

INSERT INTO tasks SET user_id = 2,
                     project_id = 3,
                     task_name = 'Сделать задание первого раздела',
                     task_deadline = '2020-12-21',
                     task_done = 1

INSERT INTO tasks SET user_id = 2,
                     project_id = 2,
                     task_name = 'Встреча с другом',
                     task_deadline = '2020-12-22',
                     task_done = 0

INSERT INTO tasks SET user_id = 2,
                     project_id = 5,
                     task_name = 'Купить корм для кота',
                     task_deadline = null;
                     task_done = 0

INSERT INTO tasks SET user_id = 2,
                     project_id = 5,
                     task_name = 'Заказать пиццу',
                     task_deadline = null,
                     task_done = 0

/*Получаем все проекты для одного пользователя Роман*/

SELECT * FROM projects WHERE user_id = 2;

/*Получаем все задачи для одного проекта для одного пользователя Роман*/

SELECT * FROM tasks WHERE user_id = 2 AND project_id = 4;

/*Помечаем задачу как выполненную*/

UPDATE tasks SET task_done = 1 WHERE id = 4;

/*обновить название задачи по её идентификатору*/

UPDATE tasks SET task_name = 'Записаться в тренажёрный зал' WHERE id = 6;
