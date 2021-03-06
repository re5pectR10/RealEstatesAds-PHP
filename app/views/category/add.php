<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
/* @var $category \Models\ViewModels\CategoryViewModel */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="col-md-12">

                <?= Form::open(array('action' => Common::getBaseURL().$action)) ?>
                <?= Form::text(array('name' => 'name', 'placeholder' => 'Name', 'value' => isset($category) ? $category->name : '', 'class' => 'form-control', 'required' => '')) ?>
                <?= Form::submit(array('name' => 'submit', 'value' => $submit, 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>