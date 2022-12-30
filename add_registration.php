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
  //만약 STUDENT 테이블에 입력한 std_id가 없으면 새로 추가한다.
  $sql = "SELECT * FROM STUDENT WHERE ID='$std_id'";
  $result = mysqli_query($link, $sql);
  if(mysqli_num_rows($result) == 0) {
    $sql = "INSERT INTO STUDENT (ID) VALUES ('$std_id')";
    mysqli_query($link, $sql);
  }

  //2학기에 개설되지 않은 교과목 신청 불가
  $sql = "SELECT * FROM COURSE WHERE
          CourseCode='$course_code'";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) == 0) {
    print 'Course '.$course_code.' does not exist.';
    exit;
  }

  //한 학생에 대해 신청하는 과목이 이미 relation이 존재할 경우 insert 불가 
  $sql = "SELECT * FROM REGISTRATION WHERE
          StdID='$std_id' AND CCode='$course_code'";
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) > 0) {
    print "The registration already exists.";
    exit;
  }
  // 한 학생에 대해 신청한 교과목들의 총 이수학점은 최소 15점 이상이여야 하며 24점을 넘을 수  없음
  $sql = "SELECT SUM(COURSE.Credit) AS TotalCredits FROM REGISTRATION, COURSE
          WHERE REGISTRATION.StdID='$std_id' AND REGISTRATION.CCode=COURSE.CourseCode";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result); // get the first row
  $sum1 = $row['TotalCredits'];  // get the value of the TotalCredits column

  $sql = "SELECT Credit FROM COURSE WHERE CourseCode='$course_code'";
  $result = mysqli_query($link, $sql);
  $row = mysqli_fetch_assoc($result); // get the first row
  $sum2 = $row['Credit'];  // get the value of the Credit column

  if ($sum1 + $sum2 > 24) {
    print "The student cannot register for more than 24 credits.";
    exit;
  }

  // 한 학생에 대해 신청한 교과목 중 교양 과목은 최대 3개까지 허용됨 
  // 먼저 course_code에 해당하는 교과목의 Type이 '교양'인지 확인
  $sql = "SELECT Type FROM COURSE WHERE CourseCode='$course_code'";
  $result = mysqli_query($link, $sql);
  //만약 교과목의 Type이 '교양'이면, std_id에 해당하는 학생이 교양 과목을 3개 이상 수강했는지 확인한다.
  if (mysqli_fetch_assoc($result)['Type'] == '교양') {
    $sql = "SELECT COUNT(*) AS NumOfElectives FROM REGISTRATION, COURSE
            WHERE StdID='$std_id' AND REGISTRATION.CCode=COURSE.CourseCode AND Type='교양'";
    $result = mysqli_query($link, $sql);
    $num_of_electives = mysqli_fetch_assoc($result)['NumOfElectives'];
    if ($num_of_electives >= 3) {
      print "The student cannot register for more than 3 elective courses.";
      exit;
    }
  }

  //한  학생에  대해  신청한  교과목들은  서로  시간이  겹치면  안됨
  //먼저 course_code에 해당하는 교과목의 Time이 겹치는지 확인
  // $sql = "SELECT CourseTime1Day FROM COURSE WHERE CourseCode='$course_code'";
  // // REGISTRATION에 있는 StdID가 std_id인 교과목들의 Time을 가져온다.
  // $sql = "SELECT CourseTime1Day, CourseTime2Day FROM REGISTRATION, COURSE
  //         WHERE StdID='$std_id' AND REGISTRATION.CCode=COURSE.CourseCode";


  //모든  교과목의  수강정원은  2명으로  최대  2명까지  수강이  가능함 
  $sql = "SELECT COUNT(*) AS NumOfStudents FROM REGISTRATION
          WHERE CCode='$course_code'";
  $result = mysqli_query($link, $sql);
  $num_of_students = mysqli_fetch_assoc($result)['NumOfStudents'];
  if ($num_of_students >= 2) {
    print "The course is full.";
    exit;
  }

  // insert the new registration
  $sql = "INSERT INTO REGISTRATION (StdID, CCode) VALUES ('$std_id', '$course_code')";
  if (mysqli_query($link, $sql)) {
    print "The registration is added successfully.";
  } else {
    print "Error: " . $sql . "<br>" . mysqli_error($link);
  }
  
  print "<hr>";
  //make button that go back to the main page
  print "<form action='sugang_index.php' method='post'>";
  print "<input type='submit' value='Go back to the main page'>";
  print "</form>";

  mysqli_close($link);

?>
