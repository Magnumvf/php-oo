<section class="row">
    <div class="col">
        <img src="/assets/img/add_course.svg" width="100%">
    </div>
    <div class="col">
        <form action=<?= "/cursos/editar?id={$curso->id}" ?> method="post">
            <label for="name"><?= translate('course-name') ?></label>
            <input value="<?= $curso->name; ?>" id="name" type="text" class="form-control mb-3" name="name" placeholder="<?= translate('input-type-here') ?>">


            <label for="description"><?= translate('course-description') ?></label>
            <input value="<?= $curso->description; ?>" id="description" type="text" class="form-control mb-3" name="description" placeholder="<?= translate('input-type-here') ?>">

            <select id="status" name="status" class="form-select mb-3" aria-label="Default select example">
                <option <?= $curso->status === true ?'selected':'' ?> value="1">Ativo</option>
                <option <?= $curso->status === false ?'selected':'' ?> value="0">Inativo</option>
            </select>

            <select id="types" name="types" class="form-select mb-3" aria-label="Default select example">
                <?php
                    foreach ($curso->getTypes() as $key => $value) {
                        if ($curso->types === $key) {
                            echo "<option selected value='{$key}'>{$value}</option>";
                        } else {
                            echo "<option value='{$key}'>{$value}</option>";
                        }
                    }
                ?>
            </select>

            <button type="submit" class="btn btn-outline-success w-100 mt-3">
                <?= translate('text-save') ?>
            </button>
        </form>
    </div>
</section>