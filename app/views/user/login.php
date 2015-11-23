<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\Session\Session;
use \FW\HTML\Form;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <?= Form::open(array('action' => Common::getBaseURL().'/user/login')) ?>
                <?= Form::text(array('name' => 'username', 'placeholder' => 'username', 'class' => 'form-control')) ?>
                <?= Form::password(array('name' => 'password', 'placeholder' => 'password', 'class' => 'form-control')) ?>
                <?= Form::submit(array('name' => 'submit', 'value' => 'Log In', 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>