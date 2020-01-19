<?php

include('User.php');


//Get Users from DB
try {
    //$dbcon = new PDO('mysql:host=5.153.13.148;dbname=kfkfk_test_db;charset=utf8', 'kfkfk_user_test', 'LKo7Xk5JdY8icAeH');  //Kfir's connection string
    $dbcon = new PDO('mysql:host=localhost;dbname=kfkfk_test_db;charset=utf8', 'root', 'root'); //my connection string
    $sql = "SELECT * FROM kfkfk_user_test";
    $query = $dbcon->prepare($sql);
    $query->execute();
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    throw $e;
}

//Creating User Objects from received Data
    foreach ($users as $user) {
        try{
            $user = new User($user['id'], $user['email'], $user['Phone'], $user['token'], $user['IP']);
            $usersArray[] = $user;
        } catch (Exception $e) {
            throw $e;
        }

    }

//Getting Users Country from API using his ip as parameter
    foreach ($usersArray as $user) {
        try {
            $ip = $user->get_ip();
            $ch = curl_init('http://appslabs.net/mobile-brain-test/cudade.php');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "ip=$ip");
     

            // Submit the POST request
            $result = curl_exec($ch);
            $json = json_decode($result, true);
            $user->set_country_name($json['theCountry']);
            $user->set_country_code($json['countryCode']);
     
            // Close cURL session handle
            curl_close($ch);
        } catch (Exception $e) {
            throw $e;
        }
    } 

//Getting Country Flag using Users Location    
    $opts = array(
        'http'=>array(
        'method'=>"GET",
        'header'=>"Content-type: image/gif" .
              "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:51.0) Gecko/20100101 Firefox/51.0"
        ));
        $context = stream_context_create($opts);
        foreach($usersArray as $user){
            try{
                $code = $user->get_country_code();
                $url = "http://appslabs.net/mobile-brain-test/images/flags/$code.gif";
                $image = base64_encode(file_get_contents($url, false, $context));
                $user->set_country_image( $image );
            }catch (Exception $e) {
                throw $e;
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
    <h1 class="text-center my-3">List of Users:</h1>
    <table class="table mx-3">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Email</th>
      <th scope="col">Phone</th>
      <th scope="col">Location</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($usersArray as $user) : ?>
    <tr>
      <td><?= $user->get_id(); ?></td>
      <td><?= $user->get_email(); ?></td>
      <td><?= $user->get_phone() ;?></td>
      <td><?= $user->get_country_name() . " " . '<img src="data:image/gif;base64,'. $user->get_country_image() .'">'?></td>
    </tr>
    <?php endforeach; ?>
        <div class="my-3 mx-3">
            <a class="btn btn-primary" href="add_user.php">
                <i class="fas fa-plus-circle"> Add User</i>
            </a>
        </div>
  </tbody>
</table>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>