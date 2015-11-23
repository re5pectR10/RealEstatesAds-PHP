<?php
use \FW\View\View;
use \FW\Helpers\Common;
use \FW\Session\Session;
?>
<?= View::getLayoutData('header') ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-md-12">
                <?php if(Session::hasMessage()): ?>
                    <div class="alert alert-success" role="alert"><?= Session::getMessage() ?></div>
                <?php endif; ?>
                <?php if(Session::hasError()): ?>
                    <div class="alert alert-danger" role="alert"><?= Session::getError() ?></div>
                <?php endif; ?>
                <h1>Users</h1>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Tools</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach($users as $u):
                        ?>
                        <tr>
                            <td><?= $u['username'] ?></td>
                            <td><?= $u['role'] ?></td>
                            <td>
                            <?php if($u['role'] == 'admin'): ?>
                                <a class="btn btn-warning" href="<?= Common::getBaseURL() ?>/admin/make/<?= $u['id'] ?>/user">Make User</a>
                            <?php else: ?>
                                <a class="btn btn-primary" href="<?= Common::getBaseURL() ?>/admin/make/<?= $u['id'] ?>/admin">Make Admin</a>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

<?= View::getLayoutData('footer') ?>