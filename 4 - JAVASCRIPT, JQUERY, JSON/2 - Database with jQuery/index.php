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
$stmt = $pdo->query("SELECT * FROM profile");

//if($row == false){
  //echo("No rows found");
//}

echo("<tr>");
echo("<th>Name</th>");
echo("<th>Headline</th>");
echo("<th>Action</th>");
echo("</tr>");

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>";
    echo(htmlentities($row['first_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td><td>");
    echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
    echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
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
