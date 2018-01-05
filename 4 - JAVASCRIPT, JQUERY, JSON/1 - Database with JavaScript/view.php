<?php
require_once "pdo.php";
?>

<html>
<head>
<title>Daniele Vergara DB</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">

<?php
$stmt = $pdo->query("SELECT * FROM profile");

//if($row == false){
  //echo("No rows found");
//}
  echo('<table border="1">'."\n");

  echo("<tr>");
  echo("<th>Name</th>");
  echo("<th>Headline</th>");
  echo("</tr>");

while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr><td>";
    echo(htmlentities($row['first_name']));
    echo("</td><td>");
    echo(htmlentities($row['headline']));
    echo("</td></tr>\n");
  }
  ?>

</div>
</body>
</html>
