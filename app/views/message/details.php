<?php
use \FW\View\View;
/* @var $message \Models\ViewModels\MessageViewModel */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="item">

            <h5><?= $message->created_at ?></h5>
            <p><?= $message->first_name ?> <?= $message->last_name ?></p>
            <p><?= $message->email ?></p>
            <p><?= $message->phone ?></p>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>