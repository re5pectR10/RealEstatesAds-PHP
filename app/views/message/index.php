<?php
use \FW\View\View;
use \FW\Helpers\Common;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="list-group">
            <?php /* @var $messages array */ ?>
            <?php foreach($messages as $m): ?>
                <div class="list-group-item">

                    <div <?= $m['is_read'] ? '' : 'class="font-bold"' ?>>
                        <a class="btn bg-success pull-right" href="<?= Common::getBaseURL() ?>/admin/message/<?= $m['id'] ?>">Details</a>
                        <p><?= $m['first_name'] ?> <?= $m['last_name'] ?></p>
                        <p><?= $m['email'] ?></p>
                        <p><?= $m['phone'] ?></p>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>

<?= View::getLayoutData('footer') ?>