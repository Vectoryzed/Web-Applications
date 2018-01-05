<?php
require_once "pdo.php";
require_once "util.php";
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

    $msg = validatePos();
    if(is_string($msg)){
        $_SESSION['error'] = $msg;
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
    }


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

    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id = :pid');
    $stmt->execute(array(
      ':pid' => $_GET['profile_id']
    ));

    $rank = 1;
    for($i=1; $i <= 9; $i++){
      if ( ! isset($_POST['year'.$i])) continue;
      if ( ! isset($_POST['desc'.$i])) continue;
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];

      $stmt = $pdo->prepare('INSERT INTO Position
      (profile_id, rank, year, description) VALUES (:pid, :rnk, :year, :descr)');
      $stmt->execute(array(
        ':pid' => $_GET['profile_id'],
        ':rnk' => $rank,
        ':year' => $year,
        ':descr' => $desc
      ));
      $rank++;
    }

    $_SESSION['success'] = 'Record updated';
    header( 'Location: index.php' ) ;
    return;
  }
}

$position = loadPos($pdo, $_GET['profile_id']);

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
<?php require_once "bootstrap.php"; require_once "head.php"; ?>
</head>
<body>
<div class="container">
<h1>Editing Profile for <?= htmlentities($_SESSION['name']); ?></h1>

<?php
// Flash pattern
if ( isset($_SESSION['error']) ) {
  echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
  unset($_SESSION['error']);
}
?>

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
<input type="hidden" name="profile_id" value="<?= htmlentities($_GET['profile_id']); ?>"/>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">

</div>
<p>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>
countPos = 0;

$(document).ready(function(){
window.console && console.log('Document ready called');

$('#addPos').click(function(event){
event.preventDefault();

if(countPos >= 9){
  alert("Maximum of nine position entries exceeded");
  return;
}

countPos++;
window.console && console.log("Adding position" + countPos);
$('#position_fields').append(
  '<div id="position' + countPos +'" \
  <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
  <input type="button" value="-" \
  onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
  <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea> \
  </div>');
});

});
</script>

</div>
</body>
</html>
