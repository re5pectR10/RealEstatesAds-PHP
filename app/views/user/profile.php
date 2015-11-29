<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
/* @var $user \Models\UserModel */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <?= Form::open(array('action' => Common::getBaseURL().'/user', 'class' => 'form-horizontal')) ?>

                <div class="form-group">
                    <?= Form::label('Username (cant change it)', array('for' => 'username', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'username', 'value' => $user->username, ' disabled' => 'true', 'class' => 'form-control')) ?>
                </div>
                <div class="form-group">
                    <?= Form::label('Email', array('for' => 'email', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'email', 'id' => 'email', 'value' => $user->email, 'class' => 'form-control')) ?>
                </div>
                <div class="form-group">
                    <?= Form::label('New Password', array('for' => 'new_password', 'class' => 'control-label')) ?>
                    <?= Form::password(array('name' => 'new_password', 'id' => 'new_password', 'placeholder' => 'New Password', 'class' => 'form-control')) ?>
                </div>
                <div class="form-group">
                    <?= Form::label('Current Password', array('for' => 'password', 'class' => 'control-label')) ?>
                    <?= Form::password(array('name' => 'password', 'id' => 'password', 'placeholder' => 'Current Password', 'class' => 'form-control')) ?>
                </div>

                <?= Form::submit(array('name' => 'submit', 'value' => 'Change', 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>