<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION['name']) == false) {
die('ACCESS DENIED');
}

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage'])) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: add.php");
        return;
    }

    else if ( is_numeric($_POST['year']) == false || is_numeric($_POST['mileage']) == false ) {
        $_SESSION['error'] = 'Year must be an integer';
        header("Location: add.php");
        return;
    }
    else {
    $sql = "INSERT INTO autos (make, model, year, mileage)
              VALUES (:makemrk, :modelmrk, :yearmrk, :mileagemrk)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':makemrk' => $_POST['make'],
        ':modelmrk' => $_POST['model'],
        ':yearmrk' => $_POST['year'],
        ':mileagemrk' => $_POST['mileage'],));
    $_SESSION['success'] = 'Record Added';
    header( 'Location: index.php' ) ;
    return;
  }
}
?>

<html>
<head>
<title>Daniele Vergara DB</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">

  <?php
// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
?>

<h1>Add A New Auto</h1><br>
<form method="post">
<p>Make:
<input type="text" name="make"></p>
<p>Model:
<input type="text" name="model"></p>
<p>Year:
<input type="text" name="year"></p>
<p>Mileage:
<input type="text" name="mileage"></p>
<p><input type="submit" value="Add New"/>
<a href="index.php">Cancel</a></p>
</form>

</div>
</body>
</html>
