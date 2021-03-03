<div class="content">
    <section class="content__side">
        <h2 class="content__side-heading">Проекты</h2>

        <nav class="main-navigation">
            <ul class="main-navigation__list">
            <?php foreach ($projects as $project) : ?>
            <?php var_dump($safeId)?>
            <?php var_dump($project['id'])?>
                <li class="main-navigation__list-item <?= (($project['id'] ?? '') === $safeId) ? 'main-navigation__list-item--active':''?>">
                    <a class="main-navigation__list-item-link" href="/?id=<?=$project['id'] ?? ''; ?>"><?= htmlspecialchars($project['project_name'] ?? '') ?></a>
                    <span class="main-navigation__list-item-count"><?= countTask($allTasks, $project['id'] ?? '')?></span>
                </li>
            <?php endforeach; ?>
            </ul>
        </nav>

        <a class="button button--transparent button--plus content__side-button"
            href="/addProject.php" target="project_add">Добавить проект</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Список задач</h2>

        <form class="search-form" action="/" method="get" autocomplete="off">
            <input class="search-form__input" type="text" name="searchTasks" value="" placeholder="Поиск по задачам">

            <input class="search-form__submit" type="submit" name="submitSearch" value="Искать">
        </form>

        <div class="tasks-controls">
            <nav class="tasks-switch">
                <a href="/index.php?filter=all&id=<?=$safeId?>" class="tasks-switch__item <?php if ($safeFilter === 'all' || $safeFilter === '') : ?>
                        tasks-switch__item--active<?php endif;?>">Все задачи</a>
                <a href="/index.php?filter=today&id=<?=$safeId?>" class="tasks-switch__item   <?php if ($safeFilter === 'today') : ?>
                          tasks-switch__item--active<?php endif;?>">Повестка дня</a>
                <a href="/index.php?filter=tommorow&id=<?=$safeId?>" class="tasks-switch__item <?php if ($safeFilter === 'tommorow') : ?>
                            tasks-switch__item--active <?php endif;?>">Завтра</a>
                <a href="/index.php?filter=expired&id=<?=$safeId?>" class="tasks-switch__item <?php if ($safeFilter === 'expired') : ?>
                            tasks-switch__item--active<?php endif;?>">Просроченные</a>
            </nav>

            <label class="checkbox">
                <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
                <input class="checkbox__input visually-hidden show_completed" <?php if ($showCompleteTasks === 1) : ?>
                                                                       checked<?php endif; ?> type="checkbox">
                <span class="checkbox__text">Показывать выполненные</span>
            </label>
        </div>

        <table class="tasks">
        <?php if (empty($tasks)) : ?>
        По вашему запросу ничего не найдено
        <?php endif;?>
        <?php foreach ($tasks as $task) :?>
            <?php if (!empty($task['task_done']) && $showCompleteTasks === 0) {
                continue;
            } ?>
            <tr class="tasks__item task <?= ($task['task_done'] === '1') ? 'task--completed':''?> <?= (!empty($task['task_done'])) && !empty($task['task_deadline']) && (dateOverdue($task['task_deadline']) <= 24) ? 'task--important':''?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input task__checkbox visually-hidden" type="checkbox" value="<?=$task['id']?>">
                        <span class="checkbox__text"><?= htmlspecialchars($task['task_name'] ?? ''); ?></span>
                    </label>
                </td>
                <td>
                    <?php if (!empty($task['file'])) :?>
                    <a href="<?=$task['file']?>">
                        <img src="img\download-link.png" alt="Файл задачи">
                    </a>
                    <?php endif; ?>
                </td>
                <td class="task__date">
                    <?= (!empty($task['task_deadline'])) ? strftime("%d.%m.%Y", strtotime($task['task_deadline'])):'' ?>
                </td>
            </tr>
        <?php endforeach;?>
        </table>
    </main>
</div>