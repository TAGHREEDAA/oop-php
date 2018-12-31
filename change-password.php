<?php

require_once 'core/init.php';

$user=new User(); // get logged in user
if (!$user->isLoggedIn()){
    Redirect::to('index.php');
}
else{
    if(Input::exists()) {
        if(Token::check(Input::get('csrf-token'))) {

            $validation = new Validation();
            $validation->check($_POST, [
                'old-password' => [
                    'required' => true,
                    'min' => 6
                ],
                'password' => [
                    'required' => true,
                    'min' => 6
                ],
                'password-confirm' => [
                    'required' => true,
                    'min' => 6,
                    'matches' => 'password'
                ],

            ]);

            if ($validation->passed()) {
                // 1- make sure old password is correct
                // 2- validate new password with its confirm password
                // 3- make new salt and hash make the new password
                // 4- update

                $user = new User();
                $Password = $user->data()->password;
                $oldSalt = $user->data()->salt;
                $oldPassword = Hash::make(Input::get('old-password'), $oldSalt);
                    if ($Password === $oldPassword) // can change it
                    {
                        try{

                            $newSalt = Hash::salt(32);

                            $user->update([
                                'password'  => Hash::make(Input::get('password'), $newSalt),
                                'salt'      => $newSalt,
                            ]);

                            Session::flash('success','Password changed successfully :) ');
                            Redirect::to('index.php');
                        }catch (Exception $exception){
                            Redirect::to(404);
                        }
                    }else{
                        var_dump('The Old Password is not correct');
                    }
            } else {
                var_dump($validation->errors());
            }
        }
        else{
            throw new Exception('Token Mismatch');
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

    <title>Register</title>

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
                <label for="old-password">Old Password</label>
                <input type="password" class="form-control" id="old-password" name="old-password" placeholder="Old Password">
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
            </div>
            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="Confirm Password">
            </div>
            <input type="hidden" name="csrf-token" value="<?php echo Token::generate();?>">
            <button type="submit" class="btn btn-primary">Change</button>
        </form>
    </div>
</div>
</body>
</html>


