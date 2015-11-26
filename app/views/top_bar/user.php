<?php
use \FW\Helpers\Common;
use \FW\Security\Auth;
?>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav">
        <li>
            <a href="<?= Common::getBaseURL() ?>/user">Profile</a>
        </li>
        <li>
            <a href="<?= Common::getBaseURL() ?>/user/logout">Logout</a>
        </li>
        <?php if(Auth::isAuth()): ?>
        <li>
            <a href="<?= Common::getBaseURL() ?>/user/<?= Auth::getUserId() ?>/favourites">Your Favourites</a>
        </li>
        <?php endif ?>
        <?php if(Auth::isUserInRole(array('admin'))): ?>
            <li>
                <a href="<?= Common::getBaseURL() ?>/admin/category">Categories</a>
            </li>
            <li>
                <a href="<?= Common::getBaseURL() ?>/admin/city">Cities</a>
            </li>
            <li>
                <a href="<?= Common::getBaseURL() ?>/admin/estate/add">Add Estate</a>
            </li>
            <li>
                <a href="<?= Common::getBaseURL() ?>/admin/users">Users</a>
            </li>
            <li>
                <a href="<?= Common::getBaseURL() ?>/admin/messages">Messages</a>
            </li>
        <?php endif ?>
    </ul>
</div>