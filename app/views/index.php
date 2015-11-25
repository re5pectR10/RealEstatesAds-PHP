<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">
        <?= Form::open(array('class' => 'form-horizontal')) ?>

        <div class="row">

            <div class="col-md-12">

                <div class="row">

                    <div class="form-group  col-md-3">
                        <h3>Categories</h3>
                        <?php foreach($categories as $cat): ?>
                            <div class="checkbox">
                                <?= Form::label(Form::check(array('name' => 'category_id[]', 'value' => $cat['id'])) . $cat['name'], array('class' => 'control-label')) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group  col-md-3">
                        <h3>Cities</h3>
                        <?php foreach($cities as $city): ?>
                            <div class="checkbox">
                                <?= Form::label(Form::check(array('name' => 'city_id[]', 'value' => $city['id'])) . $city['name'], array('class' => 'control-label')) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group  col-md-3">
                        <h3>Type</h3>
                        <?php foreach($ad_type as $type): ?>
                            <div class="checkbox">
                                <?= Form::label(Form::check(array('name' => 'ad_type[]', 'value' => $type['id'])) . $type['name'], array('class' => 'control-label')) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group col-md-3">
                        <h3>Sort By:</h3>
                        <?= Form::select(array('name' => 'sort_type', 'required' => 'true', 'class' => 'form-control'), $sort_type) ?>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#advanced-search">
                        Advanced Search
                    </button>
                </div>

                <div class="col-md-2">
                    <?= Form::submit(array('name' => 'submit', 'value' => 'Search', 'class' => 'btn btn-success')) ?>
                </div>

            </div>

            <div id="advanced-search" class="collapse row">
                <div class="col-md-9">

                    <div class="col-md-2">
                        <?= Form::label('Starting Price', array('for' => 'start_price', 'class' => 'control-label')) ?>
                    </div>

                    <div class="col-md-2">
                        <?= Form::number(array('name' => 'start_price', 'id' => 'start_price', 'class' => 'form-control')) ?>
                    </div>

                    <div class="col-md-2">
                        <?= Form::label('End Price', array('for' => 'start_price', 'class' => 'control-label')) ?>
                    </div>

                    <div class="col-md-2">
                        <?= Form::number(array('name' => 'end_price', 'id' => 'start_price', 'class' => 'form-control')) ?>
                    </div>

                </div>
            </div>

        </div>

        <?= Form::close() ?>

        <?php foreach($estates as $e): ?>
            <div class="row">
                <h4><?= $e['location'] ?></h4>
            </div>
        <?php endforeach; ?>

    </div>

<?= View::getLayoutData('footer') ?>