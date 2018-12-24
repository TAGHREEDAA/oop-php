<?php
require_once 'core/init.php';

if (Input::exists()){
    if(Token::check(Input::get('csrf-token'))) {
        $validation = new Validation();
        $validation->check($_POST, [
            'username' => [
                'required' => true,
                'min' => 2,
                'max' => 10,
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ]
        ]);

        if ($validation->passed()) {
            try{
                $user = new User();
                $loginStatus = $user->login(Input::get('username'), Input::get('password'));

                if ($loginStatus){
                    echo 'Logged in';
                    Redirect::to('index.php');
                }
                else{
                    echo 'Error Happened while login';
                }

            } catch (Exception $exception){
                var_dump($exception->getMessage());
            }
        }
        else{
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

    <title>Login</title>

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
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
              <input type="hidden" name="csrf-token" value="<?php echo Token::generate();?>">
              <button type="submit" class="btn btn-primary">Login</button>
          </form>
      </div>
  </div>
  </body>
</html>
