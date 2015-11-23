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

                <?= Form::open(array('action' => Common::getBaseURL().$action, 'class' => 'form-horizontal')) ?>

                <div class="form-group">
                    <?= Form::label('Area', array('for' => 'area', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'area', 'id' => 'area' , 'placeholder' => 'Area', 'value' => isset($estate) ? $estate['area'] : '', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Price (euro)', array('for' => 'price', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'price',  'id' => 'price' ,'placeholder' => 'Price (euro)', 'value' => isset($estate) ? $estate['price'] : '', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Location', array('for' => 'location', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'location',  'id' => 'location' ,'placeholder' => 'Location', 'value' => isset($estate) ? $estate['location'] : '', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Floor', array('for' => 'floor', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'floor',  'id' => 'floor' ,'placeholder' => 'Floor', 'value' => isset($estate) ? $estate['floor'] : '', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Phone', array('for' => 'phone', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'phone',  'id' => 'phone' ,'placeholder' => 'Phone', 'value' => isset($estate) ? $estate['phone'] : '', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Description', array('for' => 'description', 'class' => 'control-label')) ?>
                    <?= Form::textarea('', array('name' => 'description', 'id' => 'description' ,'placeholder' => 'Write description here', 'rows' => '4', 'cols' => '100', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <div class="radio">
                        <?= Form::label(Form::radio(array('name' => 'is_furnished', 'value' => 1)) . 'Furnished', array('class' => 'control-label')) ?>
                    </div>
                    <div class="radio">
                        <?= Form::label(Form::radio(array('name' => 'is_furnished', 'value' => 0)) . 'Unfurnished', array('class' => 'control-label')) ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="radio">
                        <?= Form::label(Form::radio(array('name' => 'ad_type', 'value' => 1)) . 'For Sale', array('class' => 'control-label')) ?>
                    </div>
                    <div class="radio">
                        <?= Form::label(Form::radio(array('name' => 'ad_type', 'value' => 0)) . 'For Rent', array('class' => 'control-label')) ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Form::label('Category', array('class' => 'control-label')) ?>
                    <?= Form::select(array('name' => 'category_id', 'required' => 'true', 'class' => 'form-control'), $categories) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('City', array('class' => 'control-label')) ?>
                    <?= Form::select(array('name' => 'city_id', 'required' => 'true', 'class' => 'form-control'), $cities) ?>
                </div>

                <?= Form::submit(array('name' => 'submit', 'value' => $submit, 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>