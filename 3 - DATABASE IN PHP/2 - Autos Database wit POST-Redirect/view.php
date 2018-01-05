<?php
require_once "pdo.php";

session_start();

if (isset($_SESSION['name']) == false) {
die('Not logged in');
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
<h1><?php echo(htmlentities($_SESSION['name'])); ?>, view the autos inserted in the database</h1>

<?php
if ( isset($_SESSION['insert_success']) ) {
echo('<p style="color: green;">'.htmlentities($_SESSION['insert_success'])."</p>\n");
unset($_SESSION['insert_success']);
}

?>

<h1>Automobiles:</h1>

<?php
echo("<ul>");
$sql2 = "SELECT make, year, mileage FROM autos";
$stmt = $pdo->query($sql2);
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
  echo("<li>");
  echo(htmlentities($row['year'])." ".htmlentities($row['make'])." / ".htmlentities($row['mileage']));
  echo("</li>");
}
echo("</ul>");
?>

<p>
<a href="add.php">Add New</a> | <a href="logout.php">Logout</a>
</p>

</div>
</body>
</html>
