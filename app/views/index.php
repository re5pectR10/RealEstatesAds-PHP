<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
/**
 * @var $categories Models\ViewModels\CategoryViewModel[]
 * @var $cities Models\ViewModels\CityViewModel[]
 * @var $estates Models\ViewModels\EstateBasicViewModel[]
 * @var $search Models\SearchModel
 * @var $ad_type array
 * @var $sort_type array
 * @var $userFavourite array
 */
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
                                        'value' => $cat->id,
                                        (is_array($search->category_id) && in_array($cat->id, $search->category_id) ?'checked' :'')=>'')) . $cat->name,
                                    array('class' => 'control-label')) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group  col-md-3">
                        <h3>Cities</h3>
                        <?php foreach($cities as $city): ?>
                            <div class="checkbox">
                                <?= Form::label(Form::check(array('name' => 'city_id[]',
                                        'value' => $city->id,
                                        (is_array($search->city_id) && in_array($city->id, $search->city_id) ?'checked' :'')=>''
                                        )) . $city->name, array('class' => 'control-label')) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group  col-md-3">
                        <h3>Type</h3>
                        <?php foreach($ad_type as $type): ?>
                            <div class="checkbox">
                                <?= Form::label(Form::check(array('name' => 'ad_type[]',
                                        'value' => $type['id'],
                                        (is_array($search->ad_type) && in_array($type['id'], $search->ad_type) ?'checked' :'')=>''
                                        )) . $type['name'], array('class' => 'control-label')) ?>
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
                                    <?= Form::number(array('name' => 'start_price', 'value' => (isset($search) ? $search->start_price : ''), 'id' => 'start_price', 'class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('End Price', array('for' => 'end_price', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'end_price', 'value' => (isset($search) ? $search->end_price : ''), 'id' => 'end_price', 'class' => 'form-control')) ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-5 col-md-offset-2">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('Area (From)', array('for' => 'start_area', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'start_area', 'value' => (isset($search) ? $search->start_area : ''), 'id' => 'start_area', 'class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('Area (To)', array('for' => 'end_area', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'end_area', 'value' => (isset($search) ? $search->end_area : ''), 'id' => 'end_area', 'class' => 'form-control')) ?>
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
                                    <?= Form::text(array('name' => 'location', 'value' => (isset($search) ? $search->location : ''), 'id' => 'location', 'class' => 'form-control')) ?>
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
                                    <?= Form::number(array('name' => 'start_floor', 'value' => (isset($search) ? $search->start_floor : ''), 'id' => 'start_floor', 'class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= Form::label('End Floor', array('for' => 'end_floor', 'class' => 'control-label')) ?>
                                </div>

                                <div class="col-md-8">
                                    <?= Form::number(array('name' => 'end_floor', 'value' => (isset($search) ? $search->end_floor : ''), 'id' => 'end_floor', 'class' => 'form-control')) ?>
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
                                        'value' => 1,
                                        ($search->furnished == 1 ? 'checked' : '') => ''))
                                    . 'Unfurnished', array('class' => 'control-label')) ?>
                            </div>
                            <div class="radio">
                                <?= Form::label(Form::radio(array(
                                        'name' => 'furnished',
                                        'value' => 2,
                                        ($search->furnished == 2 ? 'checked' : '') => ''))
                                    . 'Furnished', array('class' => 'control-label')) ?>
                            </div>
                            <div class="radio">
                                <?= Form::label(Form::radio(array(
                                        'name' => 'furnished',
                                        'value' => 3,
                                        ((!isset($search->furnished) || $search->furnished == 3) ? 'checked' : '') => ''))
                                    . 'Both', array('class' => 'control-label')) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 col-md-offset-2">

                        <div class="form-group">
                                <div class="checkbox">
                                    <?= Form::label(Form::check(array('name' => 'has_image',
                                            'value' => 1,
                                            (isset($search) && $search->has_image == 1 ? 'checked' : '') => ''))
                                        . 'Has Image ?', array('class' => 'control-label')) ?>
                                </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <?= Form::close() ?>

        <div class="list-group">
            <?php foreach($estates as $e): ?>
                <div class="list-group-item">
                    <div class="media">

                        <?= View::includePartial('_EstateBasic', array('estate' => $e)); ?>

                        <?php if(in_array($e->id, $userFavourite)): ?>
                            <a class="btn bg-primary pull-right" href="<?= Common::getBaseURL() ?>/estate/favorites/<?= $e->id ?>/remove">Remove From Favourites</a>
                        <?php else: ?>
                            <a class="btn bg-primary pull-right" href="<?= Common::getBaseURL() ?>/estate/favorites/<?= $e->id ?>/add">Add To Favourites</a>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

<?= View::getLayoutData('footer') ?>