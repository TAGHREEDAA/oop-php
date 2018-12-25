<?php

require_once 'core/init.php';

$user=new User(); // get logged in user
if (!$user->isLoggedIn()){
    Redirect::to('index.php');
}else{
    if(Input::exists()) {
        if (Token::check(Input::get('csrf-token'))) {
            $validation = new Validation();

            $validation->check($_POST, [
                'name' => [
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                ],
                'email' => [
                    'required' => true,
                    'unique-ignore' => ['users', 'id',$user->data()->id]
                ]
            ]);

            if ($validation->passed()) {

                try {
                    // update user data
                    $user->update([
                        'email'     => Input::get('email'),
                        'name'      => Input::get('name'),
                    ]);

                    Session::flash('success','Profile is updated successfully :) ');
                    Redirect::to('index.php');

                } catch (Exception $exception) {
                    Redirect::to(404);
                }

            }
            else {
                var_dump($validation->errors());
            }

        }

    }
}

?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="assets/imgs/favicon.ico">

    <title>Update Profile</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="text-center">
<div class="container">
    <div class="col-md-4 offset-md-4" >
        <form action="" method="POST">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo escape($user->data()->name)?>" placeholder="Enter Name">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo escape($user->data()->email)?>" placeholder="Enter email">
            </div>
            <input type="hidden" name="csrf-token" value="<?php echo Token::generate();?>">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</div>
</body>
</html>



