<?php
require_once "pdo.php";
session_start();

if(!isset($_SESSION['name'])) {
  header("Location: loginscreen.php");
  return;
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
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}
echo('<table border="1">'."\n");
$stmt = $pdo->query("SELECT * FROM autos");

//if($row == false){
  //echo("No rows found");
//}
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['make']));
    echo("</td><td>");
    echo(htmlentities($row['model']));
    echo("</td><td>");
    echo(htmlentities($row['year']));
    echo("</td><td>");
    echo(htmlentities($row['mileage']));
    echo("</td><td>");
    echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
    echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
    echo("</td></tr>\n");
}


?>
</table>
<br>
<a href="add.php">Add New Entry</a> |
<a href="logout.php">Logout</a>
</div>
</body>
</html>
