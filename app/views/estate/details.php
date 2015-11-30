<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\Security\Auth;
/* @var $estate \Models\ViewModels\EstateViewModel */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="item">

            <div class="media">

                <div class="media-left">
                    <img data-toggle="lightbox" data-remote="<?=  Common::getBaseDir() . 'images/' .  $estate->image ?>" class="center-block" style="max-width: 300px;max-height: 200px" src="<?=  Common::getBaseDir() . 'images/' . (isset($estate->thumbnailName) ? $estate->thumbnailName : $estate->image) ?>" alt="No Image">
                </div>

                <div class="media-body">
                    <h3 class="pull-right"><?= $estate->price ?> EUR</h3>
                    <h5>ID: <?= $estate->id ?></h5>
                    <address><?= $estate->city ?>: <?= $estate->location ?></address>
                    <p>Category: <?= $estate->category ?></p>
                    <h4><?= $estate->area ?> m2 (<?= floor($estate->price / $estate->area) ?> EUR per m2)</h4>
                    <h3><?= $estate->ad_type == 1 ? 'For Sale' : 'For Rent' ?></h3>
                    <h4>Floor: <?= $estate->floor ?></h4>
                    <h4><?= $estate->is_furnished ? 'Furnished' : 'Unfurnished' ?></h4>
                    <h4>Phone: <?= $estate->phone ?></h4>
                    <?php if(Auth::isUserInRole(array('admin'))): ?>
                        <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $estate->id ?>/edit">Edit</a>
                        <a onclick="return confirm('Are your sure?')" class="btn btn-danger" href="<?= Common::getBaseURL() ?>/admin/estate/<?= $estate->id ?>/delete">Delete</a>
                    <?php endif ?>
                    <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/estate/<?= $estate->id ?>/message">Send Message</a>
                </div>

                <div class="media-middle">
                    <p> <?= $estate->description ?></p>
                </div>

                <?php if(isset($estate->images)): ?>
                    <div class="media-bottom">
                        <?php foreach($estate->images as $image): ?>
                            <img data-gallery="gallery" data-toggle="lightbox" data-remote="<?=  Common::getBaseDir() . 'images/' .  $image->name ?>" style="max-width: 150px;max-height: 100px" src="<?=  Common::getBaseDir() . 'images/' .  (isset($image->thumbnailName) ? $image->thumbnailName : $image->name) ?>" alt="No Image">
                        <?php endforeach; ?>
                    </div>
                <?php endif ?>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>