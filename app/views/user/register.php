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

                <?= Form::open(array('action' => Common::getBaseURL().'/user/register')) ?>
                <?= Form::text(array('name' => 'username', 'value' => isset(Session::oldInput()['username']) ? Session::oldInput()['username'] : '', 'placeholder' => 'username', 'class' => 'form-control')) ?>
                <?= Form::password(array('name' => 'password', 'placeholder' => 'password', 'class' => 'form-control')) ?>
                <?= Form::text(array('name' => 'email', 'placeholder' => 'email', 'value' => isset(Session::oldInput()['email']) ? Session::oldInput()['email'] : '', 'class' => 'form-control')) ?>
                <?= Form::submit(array('name' => 'submit', 'value' => 'Sign In', 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>

            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>