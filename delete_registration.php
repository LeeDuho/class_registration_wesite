<?php
    require_once 'dbconfig_sugang.php';               
?>

<?php
  // safely get input parameter
  $std_id = mysqli_real_escape_string($link, $_REQUEST['std_id']);	//mysqli_real_escape_string은 문자열을 SQL 쿼리에 안전하게 사용할 수 있도록 이스케이프하는데 사용된다. 
  $course_code = mysqli_real_escape_string($link, $_REQUEST['course_code']);	//$link는 dbconfig_company.php에서 정의된 변수이다. $_REQUEST는 사용자가 입력한 값을 받아오는 변수이다.

  // check input
  if (empty($std_id) || empty($course_code)) {
    print "Empty input not allowed.";
    exit;
  }
  // std_id 길이가 10자가 아니면 에러 메시지를 출력한다.
  if (strlen($std_id) != 10) {
    print "Invalid Student ID.";
    exit;
  }

  // delete the registration
  // check if the registration exists
  $sql = "SELECT * FROM REGISTRATION WHERE
          Stdid='$std_id' AND CCode='$course_code'";
  $result = mysqli_query($link, $sql);
  if(mysqli_num_rows($result) == 0) {
    print 'Registration '.$std_id.' '.$course_code.' does not exist.';
    exit;
  }

  $sql = "DELETE FROM REGISTRATION WHERE
          Stdid='$std_id' AND CCode='$course_code'";
  mysqli_query($link, $sql);
  print "Registration deleted successfully."; 

  print "<hr>";
  //make button that go back to the main page
  print "<form action='sugang_index.php' method='post'>";
  print "<input type='submit' value='Go back to the main page'>";
  print "</form>";

  // close connection
  mysqli_close($link);

?>
