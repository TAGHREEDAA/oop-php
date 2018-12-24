<?php

require_once 'core/init.php';
if(Input::exists()) {
    if(Token::check(Input::get('csrf-token'))) {

        $validation = new Validation();
        $validation->check($_POST, [
            'username' => [
                'required' => true,
                'min' => 2,
                'max' => 10,
                'unique' => 'users',
            ],
            'name' => [
                'required' => true,
                'min' => 2,
                'max' => 50,
            ],

            'email' => [
                'required' => true,
                'unique' => 'users',
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
            // do register user
        } else {
            var_dump($validation->errors());
        }
    }
    else{
        throw new Exception('Token Mismatch');
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
                  <label for="username">UserName</label>
                  <input type="text" class="form-control" id="username" name="username" value="<?php echo escape(Input::get('username'))?>" placeholder="Enter UserName">
              </div>

              <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" id="name" name="name" value="<?php echo escape(Input::get('name'))?>" placeholder="Enter Name">
              </div>
              <div class="form-group">
                  <label for="email">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" value="<?php echo escape(Input::get('email'))?>" placeholder="Enter email">
              </div>
              <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
              <div class="form-group">
                  <label for="password-confirm">Confirm Password</label>
                  <input type="password" class="form-control" id="password-confirm" name="password-confirm" placeholder="Confirm Password">
              </div>
              <input type="hidden" name="csrf-token" value="<?php echo Token::generate();?>">
              <button type="submit" class="btn btn-primary">Sign up</button>
          </form>
      </div>
  </div>
  </body>
</html>


