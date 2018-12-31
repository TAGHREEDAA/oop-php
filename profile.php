<?php

require_once 'core/init.php';


if (!$username = Input::get('username')) {
    Redirect::to('index.php');
} else {
    // get user
    $user = new User($username);
    if (!$user->exists()) {
        Redirect::to(404);
    }
}

echo '<h2>' . $user->data()->username . '</h2>';
echo '<h2>' . $user->data()->name . '</h2>';
echo '<h2>' . $user->data()->email . '</h2>';
