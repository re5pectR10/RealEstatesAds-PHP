<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\HTML\Form;
use \FW\Session\Session;
/**
 * @var $estate \Models\ViewModels\EstateViewModel
 * @var $categories array
 * @var $cities array
 */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="col-md-12">

                <?= Form::open(array('enctype' => 'multipart/form-data', 'action' => Common::getBaseURL().$action, 'class' => 'form-horizontal')) ?>

                <div class="form-group">
                    <?= Form::label('Area', array('for' => 'area', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'area', 'id' => 'area' , 'placeholder' => 'Area', 'value' => isset($estate) ? $estate->area : Session::oldInput()['area'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Price (euro)', array('for' => 'price', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'price',  'id' => 'price' ,'placeholder' => 'Price (euro)', 'value' => isset($estate) ? $estate->price : Session::oldInput()['price'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Location', array('for' => 'location', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'location',  'id' => 'location' ,'placeholder' => 'Location', 'value' => isset($estate) ? $estate->location : Session::oldInput()['location'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Floor', array('for' => 'floor', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'floor',  'id' => 'floor' ,'placeholder' => 'Floor', 'value' => isset($estate) ? $estate->floor : Session::oldInput()['floor'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Phone', array('for' => 'phone', 'class' => 'control-label')) ?>
                    <?= Form::text(array('name' => 'phone',  'id' => 'phone' ,'placeholder' => 'Phone', 'value' => isset($estate) ? $estate->phone : Session::oldInput()['phone'], 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Description', array('for' => 'description', 'class' => 'control-label')) ?>
                    <?= Form::textarea(isset($estate) ? $estate->description : Session::oldInput()['description'], array('name' => 'description', 'id' => 'description' ,'placeholder' => 'Write description here', 'rows' => '4', 'cols' => '100', 'class' => 'form-control')) ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Is Furnished?', array('class' => 'control-label')) ?>
                    <div class="radio">
                        <?= Form::label(Form::radio(array(
                                'name' => 'is_furnished',
                                'value' => 1,
                                 ((isset($estate) && $estate->is_furnished == 1) || Session::oldInput()['is_furnished'] == 1) ? 'checked' : '' => '')
                            ) . 'Furnished', array('class' => 'control-label')) ?>
                    </div>
                    <div class="radio">
                        <?= Form::label(Form::radio(array(
                                'name' => 'is_furnished',
                                'value' => 0,
                                ((isset($estate) && $estate->is_furnished != 1) || (isset(Session::oldInput()['is_furnished']) && Session::oldInput()['is_furnished'] != 1)) ? 'checked' : '' => '')
                            ) . 'Unfurnished', array('class' => 'control-label')) ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Form::label('Ad Type', array('class' => 'control-label')) ?>
                    <div class="radio">
                        <?= Form::label(Form::radio(array(
                                'name' => 'ad_type',
                                'value' => 1,
                                ((isset($estate) && $estate->ad_type == 1) || Session::oldInput()['ad_type'] == 1) ? 'checked' : '' => '')
                            ) . 'For Sale', array('class' => 'control-label')) ?>
                    </div>
                    <div class="radio">
                        <?= Form::label(Form::radio(array(
                                'name' => 'ad_type',
                                'value' => 0,
                                ((isset($estate) && $estate->ad_type != 1) || (isset(Session::oldInput()['ad_type']) && Session::oldInput()['ad_type'] != 1)) ? 'checked' : '' => '')
                            ) . 'For Rent', array('class' => 'control-label')) ?>
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

                <div class="form-group">
                    <?= Form::label('Main Image', array('class' => 'control-label')) ?>
                    <?= Form::file(array('name' => 'main_image', 'class' => 'btn btn-primary')) ?>
                    <?php if(isset($estate->image)): ?>
                        <img data-toggle="lightbox" data-remote="<?=  Common::getBaseDir() . 'images/' .  $estate->image ?>" style="max-width: 300px;max-height: 200px" src="<?=  Common::getBaseDir() . 'images/' . (isset($estate->thumbnailName) ? $estate->thumbnailName : $estate->image) ?>" alt="No Image">
                        <a class="btn bg-danger" href="<?= Common::getBaseURL() ?>/admin/image/delete/<?= $estate->main_image_id ?>">Delete</a>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <?= Form::label('Additional Images', array('class' => 'control-label')) ?>
                    <?= Form::file(array('name' => 'images[]', 'multiple' => '', 'class' => 'btn btn-primary')) ?>
                </div>

                <?= Form::submit(array('name' => 'submit', 'value' => $submit, 'class' => 'btn btn-success')) ?>
                <?= Form::close() ?>
            </div>

        </div>

        <div class="row">
            <?php if(isset($estate->images)): ?>
                <div class="media-bottom">
                    <?php foreach($estate->images as $image): ?>
                        <img data-gallery="gallery" data-toggle="lightbox" data-remote="<?=  Common::getBaseDir() . 'images/' .  $image->name ?>" style="max-width: 150px;max-height: 100px" src="<?=  Common::getBaseDir() . 'images/' .  (isset($image->thumbnailName) ? $image->thumbnailName : $image->name) ?>" alt="No Image">
                        <a class="btn bg-danger" href="<?= Common::getBaseURL() ?>/admin/image/delete/<?= $image->id ?>">Delete</a>
                    <?php endforeach; ?>
                </div>
            <?php endif ?>
        </div>

    </div>

<?= View::getLayoutData('footer') ?>