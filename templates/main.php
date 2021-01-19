<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
            <?php foreach ($projects as $project): ?>
                <li class="main-navigation__list-item <?= ($project['id'] == $_GET['id']) ? 'main-navigation__list-item--active':''?>">
                    <a class="main-navigation__list-item-link" href="/?id=<?=$project['id']; ?>"><?= $project['project_name'] ?></a>
                    <span class="main-navigation__list-item-count"><?= count_task($allTasks, $project['id'])?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
            href="pages/form-project.html" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="index.php" method="post" autocomplete="off">
            <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
                <a href="/" class="tasks-switch__item">Повестка дня</a>
                <a href="/" class="tasks-switch__item">Завтра</a>
                <a href="/" class="tasks-switch__item">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" <?php if ($showCompleteTasks === 1): ?>checked<?php endif; ?> type="checkbox">
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
        <?php foreach ($tasks as $task):?>
            <?php if ($task['task_done'] === '1' && $showCompleteTasks === 0) {continue;} ?>
            <tr class="tasks__item task <?= ($task['task_done'] === '1') ? 'task--completed':''?> <?= ($task['task_done'] !== '1') && !empty($task['task_deadline']) && (date_overdue($task['task_deadline']) <= 24) ? 'task--important':''?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox">
                        <span class="checkbox__text"><?= htmlspecialchars($task['task_name']); ?></span>
                    </label>
                </td>
                <td>
                    <?php if (!empty($task['file'])):?>
                    <a href="<?=$task['file']?>">
                        <img src="img\download-link.png" alt="Файл задачи">
                    </a>
                    <?php endif; ?>
                </td>
                <td class="task__date">
                    <?= (!empty($task['task_deadline'])) ? strftime("%d.%m.%Y", strtotime($task['task_deadline'])):'' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </table>
    </main>
</div>