<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
use \FW\Security\Auth;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">
        <?= Form::open(array('class' => 'form-horizontal','method'=>'GET'), false) ?>

        <div class="row">

            <div class="col-md-12">

                <div class="row">

                    <div class="form-group  col-md-3">
                        <h3>Categories</h3>
                        <?php foreach($categories as $cat): ?>
                            <div class="checkbox">
                                <?= Form::label(Form::check(array('name' => 'category_id[]',
                                        'value' => $cat['id'],
                                        (is_array($search->category_id) && in_array($cat['id'], $search->category_id) ?'checked' :'')=>'')) . $cat['name'],
                                    array('class' => 'control-label')) ?>
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

            <div id="advanced-search" class="collapse">

                <div class="row">

                    <div class="col-md-5">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('Starting Price', array('for' => 'start_price', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'start_price', 'id' => 'start_price', 'class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('End Price', array('for' => 'end_price', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'end_price', 'id' => 'end_price', 'class' => 'form-control')) ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-5 col-md-offset-2">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('Starting Area', array('for' => 'start_area', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'start_area', 'id' => 'start_area', 'class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('End Area', array('for' => 'end_area', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'end_area', 'id' => 'end_area', 'class' => 'form-control')) ?>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('Location', array('for' => 'location', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::text(array('name' => 'location', 'id' => 'location', 'class' => 'form-control')) ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-5 col-md-offset-2">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('Starting Floor', array('for' => 'start_floor', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'start_floor', 'id' => 'start_floor', 'class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('End Floor', array('for' => 'end_floor', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'end_floor', 'id' => 'end_floor', 'class' => 'form-control')) ?>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <div class="radio">
                                <?= Form::label(Form::radio(array(
                                            'name' => 'furnished',
                                            'value' => 1))
                                    . 'Unfurnished', array('class' => 'control-label')) ?>
                            </div>
                            <div class="radio">
                                <?= Form::label(Form::radio(array(
                                        'name' => 'furnished',
                                        'value' => 2))
                                    . 'Furnished', array('class' => 'control-label')) ?>
                            </div>
                            <div class="radio">
                                <?= Form::label(Form::radio(array(
                                        'name' => 'furnished',
                                        'value' => 3))
                                    . 'Both', array('class' => 'control-label')) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 col-md-offset-2">

                        <div class="form-group">
                                <div class="checkbox">
                                    <?= Form::label(Form::check(array('name' => 'has_image', 'value' => 1)) . 'Has Image ?', array('class' => 'control-label')) ?>
                                </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?= Form::close() ?>

        <div class="list-group">
            <?php /* @var $estates array */ ?>
            <?php foreach($estates as $e): ?>
                <div class="list-group-item">
                    <div class="media">

                        <div class="media-left">
                            <img style="max-width: 150px;max-height: 100px" src="<?=  Common::getBaseURL() . '/images/' .  $e['name'] ?>" alt="No Image">
                        </div>

                        <div class="media-body">
                            <h3 class="pull-right"><?= $e['price'] ?> EUR</h3>
                            <h5>ID: <?= $e['id'] ?></h5>
                            <address><?= $e['city'] ?>: <?= $e['location'] ?></address>
                            <p>Category: <?= $e['category'] ?></p>
                            <a class="btn bg-success pull-right" href="<?= Common::getBaseURL() ?>/estate/<?= $e['id'] ?>">Details</a>
                            <h4><?= $e['area'] ?> m2 (<?= $e['ad_type'] == 1 ? 'For Sale' : 'For Rent' ?>)</h4>
                            <?php if(Auth::isUserInRole(array('admin'))): ?>
                                <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $e['id'] ?>/edit">Edit</a>
                            <?php endif ?>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

<?= View::getLayoutData('footer') ?>