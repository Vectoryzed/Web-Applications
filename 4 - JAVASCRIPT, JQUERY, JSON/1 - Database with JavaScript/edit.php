<?php
require_once "pdo.php";
session_start();

if (isset($_SESSION['name']) == false) {
die('Not logged in');
}

if( isset($_POST['cancel'])){
  header('Location: index.php');
  return;
}

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])) {

       // Data validation
       if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
           $_SESSION['error'] = 'All fields are required';
           header("Location: edit.php");
           return;
       }
       else if (strpos($_POST['email'], '@') == false){
         $_SESSION['error'] = 'The mail must have an at (@) sign';
         header("Location: edit.php");
         return;
       }
       else {
    $sql = "UPDATE profile SET first_name = :fn, last_name = :ln,
            email = :em, headline = :he, summary = :su
            WHERE profile_id = :p_idmrk";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
      ':fn' => $_POST['first_name'],
      ':ln' => $_POST['last_name'],
      ':em' => $_POST['email'],
      ':he' => $_POST['headline'],
      ':su' => $_POST['summary'],
      ':p_idmrk' => $_POST['profile_id'])
    );

    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
  }
}

// Guardian should go here (see delete.php)

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$he = htmlentities($row['headline']);
$su = htmlentities($row['summary']);
$p_id = htmlentities($row['profile_id']);
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

<h1>Editing Profile for UMSI</h1>
<form method="post" action="edit.php">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $em ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $he ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"><?= $su ?></textarea>
<p>
<input type="hidden" name="profile_id" value="<?= $p_id ?>"/>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>
</body>
</html>
