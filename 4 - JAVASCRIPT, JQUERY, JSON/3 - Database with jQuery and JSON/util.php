<?php

function ValidatePos(){
  for($i=0; $i <= 9; $i++){
      if ( ! isset($_POST['year'.$i])) continue;
      if ( ! isset($_POST['desc'.$i])) continue;
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];

      if(strlen($year) == 0 || strlen($desc) == 0){
        return "All fields are required";
      }
      if( ! is_numeric($year)){
        return "Position year must be numeric";
      }

      return true;
  }
}

function ValidateEdu(){
  for($i=0; $i <= 9; $i++){
      if ( ! isset($_POST['edu_year'.$i])) continue;
      if ( ! isset($_POST['edu_school'.$i])) continue;
      $year = $_POST['edu_year'.$i];
      $school = $_POST['edu_school'.$i];

      if(strlen($year) == 0 || strlen($school) == 0){
        return "All fields are required";
      }
      if( ! is_numeric($year)){
        return "Education year must be numeric";
      }

      return true;
  }
}


function loadPos($pdo, $profile_id){
  $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
  $stmt-> execute(array(
    ':prof' => $profile_id
  ));
  $positions = array();
  while ( $row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $positions[] = $row;
  }
}
