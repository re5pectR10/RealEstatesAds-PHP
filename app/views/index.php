<?php
use \FW\View\View;
use \FW\Helpers\Common;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <?= View::getLayoutData('catMenu') ?>
            <?php if($isEditor): ?>
                <a href="<?= Common::getBaseURL() ?>/product/add" class="btn btn-success">Add Product</a>
            <?php endif ?>
            <div class="col-md-9">


            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>