<?php
    require "../private/autoload.php";
    $Error = "";
    $email = "";
    $username = "";

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $email = $_POST['email'];

        // Regular Expression checking email
        if(!preg_match("/^[\w\-]+@[\w\-]+.[\w\-]+$/", $email)){

            $Error = "Please enter a valid email.";
        }
        
        $date = date("Y-m-d H:i:s");
        $url_address = get_random_string(60);
        
        $username = trim($_POST['username']);
        if(!preg_match("/^[a-zA-Z]+$/", $username)){

            $Error = "Please enter a valid username";
        }
        $username = esc($username);
        $password = esc($_POST['password']);

            //check if email exists
            $arr['email'] = $email;

            $query = "select * from users where email  = :email limit 1";
            $stm = $connection->prepare($query);
            $check = $stm->execute($arr);

            if($check){

                $data = $stm->fetchAll(PDO::FETCH_OBJ);
                if(is_array($data) && count($data) > 0) {

                    $Error = "Email already in use";
                }
            }

        if($Error == ""){

            $arr['url_address'] = $url_address;
            $arr['date'] = $date;
            $arr['username'] = $username;
            $arr['password'] = $password;
            $arr['email'] = $email;
            
            $query = "insert into users (url_address, username, password, email, date) values (:url_address, :username, :password, :email, :date)";
            $stm = $connection->prepare($query);
            $stm->execute($arr);

            header("Location: login.php");
            die;
        }
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
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
        <div id="title">Signup</div>
        <input id="textbox" type="text" name="username" value="<?=$username?>" required><br>
        <input id="textbox" type="email" name="email" value="<?=$email?>" required><br>
        <input id="textbox" type="password" name="password" required><br><br>
        <input type="submit" value="Signup">
        <a href="login.php">Login</a>
    </form>

</body>
</html>