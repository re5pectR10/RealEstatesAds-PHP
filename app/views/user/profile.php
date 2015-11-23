<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <?= Form::open(array('action' => Common::getBaseURL().'/user')) ?>
                <?= Form::text(array('name' => 'username', 'value' => $user['username'], ' disabled' => 'true')) ?>
                <?= Form::text(array('name' => 'email', 'value' => $user['email'])) ?>
                <?= Form::password(array('name' => 'new_password', 'placeholder' => 'New Password')) ?>
                <?= Form::password(array('name' => 'password', 'placeholder' => 'Current Password')) ?>
                <?= Form::submit(array('name' => 'submit', 'value' => 'Change In')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>