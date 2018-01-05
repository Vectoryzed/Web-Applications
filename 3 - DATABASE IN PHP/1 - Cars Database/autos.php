<?php
require_once "pdo.php";

if (isset($_GET['name']) == false || strlen($_GET['name']) < 1){
  die("Name parameter missing");
}

if(isset($_POST['logout']) == true){
  header("Location: index.php");
}

$user_name = $_GET['name'];

$numeric = false;
$make_empty = false;
$insert_success = false;

if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){
  if((is_numeric($_POST['year'])) == false || (is_numeric($_POST['mileage']) == false)){
    $numeric = "Mileage and year must be numeric";
  }
  else if(strlen($_POST['make']) < 1){
    $make_empty = "Make is required";
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
    $insert_success = "Record inserted";
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
<h1>Welcome <?php echo($user_name); ?> to Tracking Autos Database</h1>

<?php
if($numeric != false){
  echo('<p style="color: red;">'.htmlentities($numeric)."</p>");
}
else if($make_empty != false){
  echo('<p style="color: red;">'.htmlentities($make_empty)."</p>");
}
else if($insert_success != false){
  echo('<p style="color: green;">'.htmlentities($insert_success)."</p>");
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

<h1>Automobiles:</h1>

<?php
echo("<ul>");
$sql2 = "SELECT make, year, mileage FROM autos";
$stmt = $pdo->query($sql2);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo("<li>");
  echo($row['year']." ".$row['make']." / ".$row['mileage']);
  echo("</li>");
}
echo("</ul>");
?>

</div>
</body>
</html>
