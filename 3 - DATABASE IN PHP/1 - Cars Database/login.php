<?php

if ( isset($_POST['cancel'] ) ) {
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

$failure = false;
$no_email = false;

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['who']) && isset($_POST['pass']) ) {
    if ( strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "User name and password are required";
    }
    else if(strpos($_POST['who'], '@') == false){
        $no_email = "An email must have an @";
    }
    else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
            error_log("Login success ".$_POST['who']);
            header("Location: autos.php?name=".urlencode($_POST['who']));
            return;
        } else {
            error_log("Login fail ".$_POST['who']." $check");
            $failure = "Incorrect password";
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Daniele Vergara's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php

if ($no_email != false) {
    echo('<p style="color: red;">'.htmlentities($no_email)."</p>\n");
}

if ($failure != false) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
}

?>

<form method="POST">
<label for="nam">User Name</label>
<input type="text" name="who" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is php123 -->
</p>
</div>
</body>
