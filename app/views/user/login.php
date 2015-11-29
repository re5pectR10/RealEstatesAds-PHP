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

                <?= Form::open(array('action' => Common::getBaseURL().'/user/login', 'class' => 'form-horizontal')) ?>

                <div class="form-group">
                    <?= Form::label('Username', array('for' => 'username', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'username', 'placeholder' => 'username', 'class' => 'form-control')) ?>
                </div>
                <div class="form-group">
                    <?= Form::label('Password', array('for' => 'password', 'class' => 'control-label')) ?>
                    <?= Form::password(array('name' => 'password', 'placeholder' => 'password', 'class' => 'form-control')) ?>
                </div>

                <?= Form::submit(array('name' => 'submit', 'value' => 'Log In', 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>