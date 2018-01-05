<?php
require_once "pdo.php";

session_start();

if ( ! isset($_SESSION['name']) ) {
die('Not logged in');
}

if(isset($_POST['logout']) == true){
  header("Location: logout.php");
}

//$user_name = $_GET['name'];

if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
  if((is_numeric($_POST['year'])) == false || (is_numeric($_POST['mileage']) == false)){
    $_SESSION['numeric'] = "Mileage and year must be numeric";
    header("Location: add.php");
    return;
  }
  else if(strlen($_POST['make']) < 1){
    $_SESSION['make_empty'] = "Make is required";
    header("Location: add.php");
    return;
  }
  else{
  $sql = "INSERT INTO autos(make, year, mileage) VALUES (
    :makeplc, :yearplc, :mileageplc)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':makeplc' => $_POST['make'],
      ':yearplc' => $_POST['year'],
      ':mileageplc' => $_POST['mileage']
    ));

    $_SESSION['insert_success'] = "Record inserted";
    header("Location: view.php");
    return;
  }
}

?>


<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Daniele Vergara's Autos DB</title>
</head>

<body>
  <div class="container">
<h1>Welcome <?php echo(htmlentities($_SESSION['name'])); ?> to Tracking Autos Database</h1>

<?php
if ( isset($_SESSION['numeric']) ) {
echo('<p style="color: red;">'.htmlentities($_SESSION['numeric'])."</p>\n");
unset($_SESSION['numeric']);
}
else if( isset($_SESSION['make_empty']) ) {
echo('<p style="color: red;">'.htmlentities($_SESSION['make_empty'])."</p>\n");
unset($_SESSION['make_empty']);
}
?>

<form method="POST">
  <label for="1">Make</label>
  <input type="text" name="make" id="1"></br>
  <label for="2">Year</label>
  <input type="text" name="year" id="2"></br>
  <label for="3">Mileage</label>
  <input type="text" name="mileage" id="3"></br>
  <input type="submit" value="Add car">
  <input type="submit" name="logout" value="Logout">
</form>

</br>

</div>
</body>
</html>
