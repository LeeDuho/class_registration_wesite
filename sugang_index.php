<?php
    // connect to mysql db
    require_once 'dbconfig_sugang.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Lab Practice Website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">[2022-2, COMP322-6] Lab practice, 14th Nov.</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">Home</a>
                    </li>
                </ul>
            </div>
            
             <!-- /.navbar-collapse -->
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">
        <br>
        <br>
        <!-- Marketing Icons Section -->
        <div class="row">
            <div class="col-lg-10">
                <h1 class="page-header">수강신청을 위한 수강꾸러미 시스템</h1>
    			<div class="jumbotron">                  
    			  <h2>학번과 수강하고자 하는 교과목 코드를 입력하세요</h2>
    			</div>
                
                <!-- 2 input fields, 3 buttons -->
                <form action="delete_registration.php" method="post">
                  <div class="form-group">
                    <label for="std_id">학번</label>
                    <input class="form-control" name="std_id" placeholder="학번을 입력하세요 (e.g., 1234567890)">
                  </div>

                  <div class="form-group">
                    <label for="course_code">교과목 코드</label>
                    <input class="form-control" name="course_code" placeholder="교과목 코드를 입력하세요 (e.g., COMP0322)">
                  </div>

                  <button type="submit" class="btn btn-default" formaction="add_registration.php">수강꾸러미에 담기</button>
                  <button type="submit" class="btn btn-default" formaction="delete_registration.php">수강꾸러미에서 제거</button>
                
                </form>
                <hr>
                <h3>수강꾸러미 조회</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <input type="text" name="search_id" class="form-control" style="width:300px; display:inline;" placeholder="학번을 입력하세요 (e.g., 1234567890)">
                    <input type="submit" class="btn btn-default" name="submit" value="조회">
                </form>
                <hr>

                <!-- view sugang table -->
                <div class="sugang_table">
                    <?php
                        $std_id = "";
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            $std_id = $_POST["search_id"];	
                        }
                        echo "<p>[$std_id] 수강꾸러미 현황</p>";

                        $sql = "SELECT CourseCode, CourseName, Credit FROM REGISTRATION, COURSE WHERE REGISTRATION.StdID = $std_id AND REGISTRATION.CCode = COURSE.CourseCode";
                        $result = mysqli_query($link, $sql);
                        
                        if (mysqli_num_rows($result) > 0) {
                            echo "<table class='table table-striped'>";
                            echo "<thead><tr><th>Course Code</th><th>Course Name</th><th>Credit</th></tr></thead>";
                            echo "<tbody>";
                            while($row = mysqli_fetch_assoc($result)) {
                                echo "<tr><td>" . $row["CourseCode"] . "</td><td>" . $row["CourseName"] . "</td><td>" . $row["Credit"] . "</td></tr>";
                            }
                            
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "0 results";
                        }
                    
                        // Close connection
                        mysqli_close($link);
                    ?>
                </div>

            </div>
        </div>
    </div>
    <!-- /.container -->
    

</body>

</html>
