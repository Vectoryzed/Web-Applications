<?php
session_start();
require_once "pdo.php";

if (isset($_GET['profile_id'])) {
	//Get data about Profile
	$data = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid');
	$data->execute(array('pid' => $_REQUEST['profile_id']));
	$row = $data->fetch(PDO::FETCH_ASSOC);

	if ($row === false) {
		$_SESSION['error'] = "Could not load profile";
		header("Location: index.php");
		return;
	}

	// Get data about Education
	$data_edu = $pdo->prepare('SELECT Education.year, Institution.name FROM Education JOIN Institution WHERE profile_id = :pid AND Education.institution_id = Institution.institution_id');
	$data_edu->execute(array('pid' => $_REQUEST['profile_id']));
	$row_edu = $data_edu->fetch(PDO::FETCH_ASSOC);

	// Get data about Position
	$data_pos = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :pid');
	$data_pos->execute(array('pid' => $_REQUEST['profile_id']));
	$row_pos = $data_pos->fetch(PDO::FETCH_ASSOC);
} else {
	$_SESSION['error'] = "Missing profile_id";
	header("Location: index.php");
	return;
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Daniele Vergara DB</title>

	<link rel="stylesheet"
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

	<script
		src="https://code.jquery.com/jquery-3.2.1.js"
		integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
		crossorigin="anonymous">
	</script>

	<script
  		src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
		integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
		crossorigin="anonymous">
	</script>
</head>
<body>
<div class="container">
	<h1>Profile information</h1>
	<p>First name: <?= htmlentities($row['first_name']) ?></p>
	<p>Last name: <?= htmlentities($row['last_name']) ?></p>
	<p>Email: <?= htmlentities($row['email']) ?></p>
	<p>Headline:<br/> <?= htmlentities($row['headline']) ?></p>
	<p>Summary:<br/> <?= htmlentities($row['summary']) ?></p>
	<?php
	if ($row_edu !== false) {
		echo "<p>Education</p>\n";
		echo "<ul>";
		while ($row_edu !== false) {
			echo '<li>'.htmlentities($row_edu['year']).': '.htmlentities($row_edu['name']).'</li>';
			$row_edu = $data_edu->fetch(PDO::FETCH_ASSOC);
		}
		echo "</ul>";
	}

	if ($row_pos !== false) {
		echo "<p>Positions</p>\n";
		echo "<ul>";
		while ($row_pos !== false) {
			echo '<li>'.htmlentities($row_pos['year']).': '.htmlentities($row_pos['description']).'</li>';
			$row_pos = $data_pos->fetch(PDO::FETCH_ASSOC);
		}
		echo "</ul>";
	}

	?>
	<a href="index.php">Done</a>
</div>
</body>
</html>
