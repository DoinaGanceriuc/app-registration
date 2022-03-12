<?php
include('dbConfig.php');
 
	$errors = array();
    $uName = $_POST['name'];
    $uLastname = $_POST['lastname'];
    $uEmail = $_POST['email'];
    $lengthName = strlen($_POST['name']);
    $lengthLastname = strlen($_POST['lastname']);
    $success = '';
 
	if(isset($_POST['send'])){
 
        if(preg_match("/\S+/", $uName) === 0){
            $errors['name'] = "* Name is required.";
        } else if($lengthName > 25) {
            $errors['name'] = "Name must be less than 25 characters!";
        }
        if(preg_match("/\S+/", $uLastname) === 0){
            $errors['lastname'] = "* Lastame is required.";
        } else if($lengthLastname > 25) {
            $errors['lastname'] = "Lastname must be less than 25 characters!";
        }
        if(preg_match('/^[a-z0-9]+@[a-z\.]+$/i', $uEmail) === 0){
            $errors['email'] = "* Not a valid e-mail address.";
        }
        if(preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $_POST['password']) === 0){
            $errors['password'] = "*Password must be at least 8 characters in length and must contain at least one number, one upper case letter, one lower case letter and one special character.";
        }
    
        if(count($errors) === 0){
            $name = mysqli_real_escape_string($conn, $uName);
            $lastname = mysqli_real_escape_string($conn, $uLastname);
            $email = mysqli_real_escape_string($conn, $uEmail);
    
            $password = $_POST['password'];
            $hash = password_hash($password, PASSWORD_DEFAULT);
    
            $search_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
            $num_row = mysqli_num_rows($search_query);
            if($num_row >= 1){
                $errors['email'] = "Email address already exists.";
                $success = '';
            }else{
                $sql = "INSERT INTO users (name,lastname,email,password) VALUES ('$name','$lastname','$email','$hash')";
                $query = mysqli_query($conn, $sql);
                $_POST['name'] = '';
                $_POST['lastname'] = '';
                $_POST['email'] = '';
                $_POST['password'] = '';
                $result = "New user created";

            }
        }
	}
  

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

    <body>

        <div class="login-page">
            <div class="form">
                <form method="POST" action="<?php $_PHP_SELF ?>">
                        <div class="mb-3">
                                <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus maxlength="25" placeholder="Type your name" value="<?php if(isset($uName)){echo $uName;} ?>" required>
                                <small class="text-muted"><?php if(isset($errors['name'])){echo $errors['name']; } ?></small>
                        </div>
                        <div class="mb-3">
                                <input id="lastname" type="text" class="form-control" name="lastname" autocomplete="lastname" autofocus maxlength="25" placeholder="Type your lastname" value="<?php if(isset($uLastname)){echo $uLastname;} ?>" required>
                                <small class="text-muted"><?php if(isset($errors['lastname'])){echo $errors['lastname']; } ?></small>
                        </div>
                        <div class="mb-3">
                                <input id="email" type="email" class="form-control" name="email" autocomplete="email" placeholder="Type your e-mail address" value="<?php if(isset($uEmail)){echo $uEmail;} ?>" required>
                                <small class="text-muted"><?php if(isset($errors['email'])){echo $errors['email']; } ?></small>
                        </div>
                        <div class="mb-3">
                                <input id="password" type="password" class="form-control" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*?[0-9])(?=.*?[!@#$%^&*+`~'=?\|\]\[\(\)\-<>/]).{8,}" autocomplete="new-password" minlength="8" placeholder="*******" required>
                                <small class="text-muted"><?php if(isset($errors['password'])){echo $errors['password']; } ?></small>
                        </div>
                        <div class="mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="text-white" name="send">
                                    Send
                                </button>
                                <small class="text-muted"><?php if(isset($result)){ echo $result; echo '<script language="javascript">setTimeout(function(){ window.location="";},1500);</script>'; } ?> </small>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </body>
</html>