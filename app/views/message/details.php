<?php
use \FW\View\View;
/* @var $message \Models\ViewModels\MessageViewModel */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="item">

            <h5><?= $message->created_at ?></h5>
            <h2><?= $message->first_name ?> <?= $message->last_name ?></h2>
            <h5><?= $message->email ?></h5>
            <h4>Phone: <?= $message->phone ?></h4>
            <p><?= $message->content ?></p>
            <div class="thumbnail">
                <p>For Estate: <?= $message->for_estate ?></p>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>