<?php

session_start();
unset($_SESSION['name']);
unset($_SESSION['user_id']);
header('Location: index.php');

?>

<html>
<head>
<title>Daniele Vergara's Logout</title>
</head>
<body>

</body>
</html>
