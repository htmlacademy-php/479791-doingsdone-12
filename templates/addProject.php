<div class="content">
  <section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
      <ul class="main-navigation__list">
        <?php foreach ($projects as $project) : ?>
            <li class="main-navigation__list-item <?= ($project['id'] == $_GET['id']) ? 'main-navigation__list-item--active':''?>">
                <a class="main-navigation__list-item-link" href="/?id=<?=$project['id']; ?>"><?= $project['project_name'] ?></a>
                <span class="main-navigation__list-item-count"><?= count_task($allTasks, $project['id'])?></span>
            </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="/addProject.php">Добавить проект</a>
  </section>
  <main class="content__main">
    <h2 class="content__main-heading">Добавление проекта</h2>

    <form class="form"  action="/addProject.php" method="post" autocomplete="off">
      <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input class="form__input <?php if (isset($errors['name'])) : ?>
              'form__input--error'<?php endif;?>" type="text" name="name" id="project_name" value="<?= isset($_POST['name']) ? ($_POST['name']) : ''; ?>" placeholder="Введите название проекта">
        <p class="form__message"><?=$errors['name'] ?></p>
        </div>

      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="submitProject" value="Добавить">
      </div>
    </form>
  </main>
</div>