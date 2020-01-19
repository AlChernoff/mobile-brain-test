<?php

//function to help user with inpit if he failed validation. Shows him inserted by him value
if(!function_exists('keep_old_value')){
    function keep_old_value($fn)
{
    return $_REQUEST[$fn] ?? '';
}
};

//Pattern to validate ip
$ip_pattern = "/^(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\."
.  "(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\."
.  "(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\."
.  "(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/";

//Array for validations
$errors = [
  'email' => '',
  'phone' => '',
  'ip' => ''
];


//Check form on submit
if (isset($_POST['submit'])) {

  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL, FILTER_FLAG_NO_ENCODE_QUOTES);
  $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $ip = filter_input(INPUT_POST, 'ip', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
  $form_valid = true;

  //Email format validation
  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
    $errors['email'] = ' * Email is required  to be a valid email  up  to 100 chars.';
    $form_valid = false;
  }

  //Email uniqness validation
  if($email){
      try{
          // $dbcon = new PDO('mysql:host=5.153.13.148;dbname=kfkfk_test_db;charset=utf8', 'kfkfk_user_test', 'LKo7Xk5JdY8icAeH');  Kfir's connection string
        $dbcon = new PDO('mysql:host=localhost;dbname=kfkfk_test_db;charset=utf8','root','root');
        $sql = "SELECT * FROM kfkfk_user_test where email=:email";
        $query = $dbcon->prepare($sql);
        $result = $query->execute([
            'email'=>$email
        ]);
    
        if ($result){
            $errors['email'] = ' * Email is already taken.';
        }
      } catch (Exception $e) {
        throw $e;
    }

  }
  
  //Phone validation
  if (!$phone ||  mb_strlen($phone) > 10 || !is_numeric($phone)) {
    $errors['phone'] = ' * Phone is required  to be valid  up  to 10 chars.';
    $form_valid = false;
  }

  //IP Validation
  if (!$ip ||  mb_strlen($ip) > 15 || !preg_match($ip_pattern, $ip) ) {


    $errors['ip'] = ' * IP is  required  an must be a valid  ip up to   15 chars.';
    $form_valid = false;
  } 

    //Insert of new User
    if ($form_valid) {
        try{
            // $dbcon = new PDO('mysql:host=5.153.13.148;dbname=kfkfk_test_db;charset=utf8', 'kfkfk_user_test', 'LKo7Xk5JdY8icAeH');  Kfir's connection string
            $dbcon = new PDO('mysql:host=localhost;dbname=kfkfk_test_db;charset=utf8','root','root');
            $token = bin2hex(random_bytes(16));
            $sql = "INSERT INTO kfkfk_user_test(email,Phone,token,IP) VALUES( :email, :phone, '$token', :ip)";
            $query = $dbcon->prepare($sql);
            $result = $query->execute([
                'email'=>$email,
                'phone' => $phone,
                'ip' => $ip
            ]);
        
            if ($result) {
              header('location: index.php');
              exit;
                throw $e;
            }
        } catch (Exception $e) {
            throw $e;
            } 
        }

}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <title>Users List</title>
  </head>
  <body>
<main class="body-color">
  <div class="container">
    <div class="row">
    <div class="col-12 my-5 text-center">
                <h1 class="text mb-3">Here you can add new User</h1>
            </div>
    </div>
    <div class="row justify-content-center align-items-center">
      <div class="col-md-6">
        <form action="" method="POST" autocomplete="off" novalidate="novalidate">
          <div class="form-group">
            <label class="text" for="email">* Email:</label>
            <input value="<?= keep_old_value('email'); ?>" type="email" name="email" id="email" class="form-control">
            <span class="text-danger"><?= $errors['email']; ?></span>
          </div>
          <div class="form-group">
            <label class="text" for="phone">* Phone:</label>
            <input value="<?= keep_old_value('phone'); ?>" type="tel" name="phone" id="phone" class="form-control">
            <span class="text-danger"><?= $errors['phone']; ?></span>
          </div>
          <div class="form-group">
            <label class="text" for="ip">* IP:</label>
            <input value="<?= keep_old_value('ip'); ?>" type="text" name="ip" id="ip" class="form-control">
            <span class="text-danger"><?= $errors['ip']; ?></span>
          </div>
          <a class="btn btn-primary" href="index.php">Cancel</a>
          <input type="submit" value="Save" name="submit" class="btn btn-primary float-right">
        </form>
      </div>
    </div>
  </div>
</main>
</body>
</html>


