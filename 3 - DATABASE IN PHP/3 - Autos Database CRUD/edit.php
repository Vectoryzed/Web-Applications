<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION['name']) == false) {
die('ACCESS DENIED');
}

if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['mileage']) && isset($_POST['year']) && isset($_POST['autos_id'])) {

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
    $sql = "UPDATE autos SET make = :makemrk, model = :modelmrk,
            year = :yearmrk, mileage = :mileagemrk
            WHERE autos_id = :autos_idmrk";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':makemrk' => $_POST['make'],
      ':modelmrk' => $_POST['model'],
      ':yearmrk' => $_POST['year'],
      ':mileagemrk' => $_POST['mileage'],
      ':autos_idmrk' => $_POST['autos_id'],));

    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
  }
}

// Guardian should go here (see delete.php)

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

$ma = htmlentities($row['make']);
$mo = htmlentities($row['model']);
$y = htmlentities($row['year']);
$mi = htmlentities($row['mileage']);
$aid = htmlentities($row['autos_id']);
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

<h1>Edit Auto</h1><br>
<form method="post">
<p>Make:
<input type="text" name="make" value="<?= $ma ?>"></p>
<p>Model:
<input type="text" name="model" value="<?= $mo ?>"></p>
<p>Year:
<input type="text" name="year" value="<?= $y ?>"></p>
<p>Mileage:
<input type="text" name="mileage" value="<?= $mi ?>"></p>
<input type="hidden" name="autos_id" value="<?= $aid ?>">
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>

</div>
</body>
</html>
