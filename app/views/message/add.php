<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
use \FW\Session\Session;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="col-md-12">

                <?= Form::open(array('class' => 'form-horizontal')) ?>

                <div class="form-group">
                    <?= Form::label('First Name', array('for' => 'first_name', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'first_name', 'id' => 'first_name' , 'placeholder' => 'Name', 'value' => Session::oldInput()['first_name'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Lst Name', array('for' => 'last_name', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'last_name', 'id' => 'last_name' , 'placeholder' => 'Name', 'value' => Session::oldInput()['last_name'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Email', array('for' => 'email', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'email', 'id' => 'email' , 'placeholder' => 'Email', 'value' => Session::oldInput()['email'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Phone', array('for' => 'phone', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'phone', 'id' => 'phone' , 'placeholder' => 'Phone', 'value' => Session::oldInput()['phone'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('About Estate', array('for' => 'about', 'class' => 'control-label')) ?>
                    <?= Form::textarea(isset($estateInfo) ? $estateInfo : Session::oldInput()['about'], array('name' => 'about', 'id' => 'about' , 'placeholder' => 'Content', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Content', array('for' => 'content', 'class' => 'control-label')) ?>
                    <?= Form::textarea(Session::oldInput()['content'], array('name' => 'content', 'id' => 'content' , 'placeholder' => 'Content', 'class' => 'form-control')) ?>
                </div>

                <?= Form::submit(array('class' => 'btn btn-success', 'value' => 'Send')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>