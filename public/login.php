<?php
    require "../private/autoload.php";
    $Error = "";
    
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['token']) && isset($_POST['token']) && $_SESSION['token'] == $_POST['token']){
        $email = $_POST['email'];

        // Regular Expression checking email
        if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email)){
            $Error = "Please enter a valid email.";
        }
        
        $password = $_POST['password'];

        if($Error == ""){
            $arr['email'] = $email;

            $query = "select * from users where email = :email limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);

            if($check){
                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0){
                    $data = $data[0];

                    if($password == $data->password) {
                        $_SESSION['username'] = $data->username;
                        $_SESSION['url_address'] = $data->url_address;
                        header("Location: index.php");
                        die;
                    } else {
                        $Error = "Wrong email or password";
                    }
                }
            }
        }
            
    }
    
    $_SESSION['token'] = get_random_string(60);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body style="font-family: verdana">
    <style type="text/css">
        form {
            margin: auto;
            border: solid thin #aaa;
            padding: 6px;
            max-width: 300px;
        }

        #title {
            background-color: grey;
            padding: .5em;
            text-align: center;
        }

        #textbox {
            border: solid thin #aaa;
            margin: 4px;
            width: 98%;
        }
    </style>
    <form method="POST">
        <div><?php 
            if(isset($Error) && $Error != ""){

                echo $Error;
            }
        ?></div>
        <div id="title">Login</div>
        <input id="textbox" type="email" name="email" required><br>
        <input id="textbox" type="password" name="password" required><br><br>
        <input type="hidden" name="token" value="<?=$_SESSION['token']?>">
        <input type="submit" value="Login">
        <a href="signup.php">Sign up</a>
    </form>

</body>
</html>