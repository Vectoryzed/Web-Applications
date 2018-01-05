<?php
session_start();

if (! isset($_SESSION['name'])) {
	die("ACCESS DENIED");
}

if (isset($_POST['cancel'])) {
	header("Location: index.php");
	return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
	require_once "pdo.php";

	// Check if all filds of main form are complete
	if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
		$_SESSION['error_add'] = 'All fields are required';
		header('Location: add.php');
		return;
	}

	// Check if email have an @-sign
	if (! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$_SESSION['error_add'] = "Email address must contain @";
		header("Location: add.php");
		return;
	}

	// Check if all filds of positions are complete
	for ($cont_year=1; $cont_year <= 9 ; $cont_year++)	{
		if (isset($_POST['year'.$cont_year]) && isset($_POST['desc'.$cont_year])) {
			if (strlen($_POST['year'.$cont_year]) < 1 || strlen($_POST['desc'.$cont_year]) < 1) {
				$_SESSION['error_add'] = 'All fields are required';
				header('Location: add.php');
				return;
			}
			// Check if year is numeric
			if (! is_numeric($_POST['year'.$cont_year.''])) {
				$_SESSION['error_add'] = "Position year must be numeric";
				header("Location: add.php");
				return;
			}
		}
	}

	// Check if all filds of education are complete
	$institution_id = array();
	for ($k=1; $k <= 9 ; $k++)	{
		if (isset($_POST['edu_year'.$k]) && isset($_POST['edu_school'.$k])) {
			if (strlen($_POST['edu_year'.$k]) < 1 || strlen($_POST['edu_school'.$k]) < 1) {
				$_SESSION['error_add'] = 'All fields are required';
				header('Location: add.php');
				return;
			}
			// Check if edu_year is numeric
			if (! is_numeric($_POST['edu_year'.$k.''])) {
				$_SESSION['error_add'] = "Year must be numeric";
				header("Location: add.php");
				return;
			}

			// Check if exist the university typed in the database, if not exist we create a new university with the data of user
			$stmt = $pdo->prepare('SELECT * FROM Institution WHERE name = :name');
			$stmt->execute(array(':name' => $_POST['edu_school'.$k]));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($row !== false) {
				$institution_id[$k] = $row['institution_id'];
			} else {
				$stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
				$stmt->execute(array(':name' => $_POST['edu_school'.$k]));

				$institution_id[$k] = $pdo->lastInsertId();
			}
		}
	}


	// Insert all the data into database
	$stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
    $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
    );
    $last_profile_id = $pdo->lastInsertId();

    $rank = 1;
    for ($i=1; $i <= 9; $i++) {
    	if (isset($_POST['year'.$i]) && isset($_POST['desc'.$i])) {
    		$stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES (:pid, :rk, :yr, :dsc)');
    		$stmt->execute(array(
    			':pid' => $last_profile_id,
    			':rk' => $rank,
    			':yr' => $_POST['year'.$i],
    			':dsc' => $_POST['desc'.$i]));
    	}
    	$rank++;
    }

    $rank = 1;
    for ($i=1; $i <= 9; $i++) {
    	if (isset($_POST['edu_year'.$i]) && isset($_POST['edu_school'.$i])) {
    		$stmt = $pdo->prepare('INSERT INTO Education (profile_id, institution_id, rank, year) VALUES (:pid, :inst, :rk, :yr)');
    		$stmt->execute(array(
    			':pid' => $last_profile_id,
    			':inst' => $institution_id[$i],
    			':rk' => $rank,
    			':yr' => $_POST['edu_year'.$i]));
    	}
    	$rank++;
    }

    $_SESSION['success'] = "Profile Added";
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
		<h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?></h1>
		<?php
		if (isset($_SESSION['error_add'])) {
			echo('<p style="color: red;">'.htmlentities($_SESSION['error_add'])."</p>\n");
			unset($_SESSION['error_add']);
		}
		?>
		<form method="post">
		<p>First Name:
		<input type="text" name="first_name" size="60"/></p>
		<p>Last Name:
		<input type="text" name="last_name" size="60"/></p>
		<p>Email:
		<input type="text" name="email" size="30"/></p>
		<p>Headline:<br/>
		<input type="text" name="headline" size="80"/></p>
		<p>Summary:<br/>
		<textarea name="summary" rows="8" cols="80"></textarea></p>
		<p>
			Education: <input type="submit" id="addEdu" value="+">
			<div id="edu_fields">
			</div>
		</p>
		<p>
			Position: <input type="submit" id="addPos" value="+">
		</p>
		<div id="position_fields">
		</div>
		<p>
		<input type="submit" value="Add">
		<input type="submit" name="cancel" value="Cancel">
		</p>
		</form>
	<script>
	var countPos = 0;
	var countEdu = 0;

	// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
	$(document).ready(function(){
	    window.console && console.log('Document ready called');
	    $('#addPos').click(function(event){
	        // http://api.jquery.com/event.preventdefault/
	        event.preventDefault();
	        if ( countPos >= 9 ) {
	            alert("Maximum of nine position entries exceeded");
	            return;
	        }
	        countPos++;
	        window.console && console.log("Adding position "+countPos);
	        $('#position_fields').append(
	            '<div id="position'+countPos+'"> \
	            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
	            <input type="button" value="-" \
	                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
	            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
	            </div>');
	    });

	    $('#addEdu').click(function(event){
        event.preventDefault();
	        if ( countEdu >= 9 ) {
	            alert("Maximum of nine education entries exceeded");
	            return;
	        }
	        countEdu++;
	        window.console && console.log("Adding education "+countEdu);

	        $('#edu_fields').append(
	            '<div id="edu'+countEdu+'"> \
	            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
	            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
	            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
	            </p></div>'
	        );

	        $('.school').autocomplete({
	            source: "school.php"
	        });

	    });
	});

  }
	</script>
	</div>
</body>
</html>
