<?php
use \FW\View\View;
use \FW\Helpers\Common;
/**
 * @var $messages \Models\ViewModels\MessageBasicViewModel[]
 * @var $currentSort string
 * @var $currentOrder string
 */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-6">
                <h3>Sort By:</h3>
                <a href="<?= Common::getBaseURL() ?>/admin/messages/created/<?= $currentSort ?>" class="btn btn-primary <?= $currentOrder == 'created' ? 'disabled' : '' ?>">Date of Creation</a>
                <a href="<?= Common::getBaseURL() ?>/admin/messages/name/<?= $currentSort ?>" class="btn btn-primary <?= $currentOrder == 'name' ? 'disabled' : '' ?>">Full Name</a>
            </div>
            <div class="col-md-6">
                <h3>Order:</h3>
                <a href="<?= Common::getBaseURL() ?>/admin/messages/<?= $currentOrder ?>" class="btn btn-primary <?= $currentSort == 'asc' ? 'disabled' : '' ?>">Ascending</a>
                <a href="<?= Common::getBaseURL() ?>/admin/messages/<?= $currentOrder ?>/desc" class="btn btn-primary <?= $currentSort == 'desc' ? 'disabled' : '' ?>">Descending</a>
            </div>
        </div>

        <div class="list-group">
            <?php foreach($messages as $m): ?>
                <div class="list-group-item">

                    <div <?= $m->is_read ? '' : 'class="font-bold"' ?>>
                        <a class="btn bg-success pull-right" href="<?= Common::getBaseURL() ?>/admin/message/<?= $m->id ?>">Details</a>
                        <p><?= $m->first_name ?> <?= $m->last_name ?></p>
                        <p><?= $m->email ?></p>
                        <p><?= $m->phone ?></p>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>

<?= View::getLayoutData('footer') ?>