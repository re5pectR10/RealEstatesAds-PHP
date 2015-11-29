<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\Security\Auth;
/* @var $estates \Models\ViewModels\EstateBasicViewModel[] */
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

    <div class="list-group">
        <?php foreach($estates as $e): ?>
            <div class="list-group-item">
                <div class="media">

                    <?= View::includePartial('_EstateBasic', array('estate' => $e)); ?>

                    <a class="btn bg-primary pull-right" href="<?= Common::getBaseURL() ?>/estate/favorites/<?= $e->id ?>/remove">Remove From Favourites</a>

                </div>
            </div>
        <?php endforeach; ?>
    </div>

    </div>

<?= View::getLayoutData('footer') ?>