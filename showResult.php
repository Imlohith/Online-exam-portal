<?php require'database.php' ?>
<?php
  session_start();
  if(!isset($_SESSION['user_id'])){
  	header("Location: index.php");
  }
  if(!isset($_SESSION['test_id'])){
    header("Location: studentHome.php");
  }
  $user_id=$_SESSION['user_id'];
  $right=0;
  $wrong=0;
  $no_attempt=0;
  $test_id=$_SESSION['test_id'];
  $query=mysqli_query($conn,"SELECT * FROM questions WHERE test_id='$test_id' ");
  $result=mysqli_fetch_all($query, MYSQLI_ASSOC);
  if(isset($_POST['submitTest'])){
    foreach ($result as $res) {
      if($_POST[$res['question_id']]==$res['answer']){
        $right++;
      }else if($_POST[$res['question_id']]=='none'){
        $no_attempt++;
      }else{
        $wrong++;
      }
    }
    $total=$right+$wrong+$no_attempt;
    $score=($right*100)/($total);
    mysqli_query($conn,"INSERT INTO test_result(test_id, user_id, right_ans, wrong_ans, no_attempt, score) VALUES('$test_id','$user_id','$right','$wrong','$no_attempt','$score')" );
    $temp_query=mysqli_query($conn,"SELECT * FROM test_result ORDER BY result_id DESC LIMIT 1");
    $temp_result=mysqli_fetch_array($temp_query, MYSQLI_ASSOC);
    $result_id=$temp_result['result_id'];
    $query_desc=mysqli_query($conn,"SELECT * FROM questions_desc WHERE test_id='$test_id' ");
    $result_desc=mysqli_fetch_all($query_desc, MYSQLI_ASSOC);
    foreach ($result_desc as $res_desc) {
      $q_id=$res_desc['question_id'];
      $temp=$_POST[$res_desc['question_id']];
      $ans=mysqli_real_escape_string($conn,$temp);
      mysqli_query($conn,"INSERT INTO test_result_desc(result_id, question_id, user_id, test_id, answer) VALUES('$result_id','$q_id', '$user_id', '$test_id', '$ans' )");
    }
  }
  unset($_SESSION["test_id"]);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Online Exam Portal - Endeavor</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
		<script type="text/javascript" src="js/solveTest.js"></script>
    <script>
      history.pushState(null, null, document.URL);
      window.addEventListener('popstate', function () {
        history.pushState(null, null, document.URL);
      });
    </script>
	</head>
  <body>
    <div class="container" style="margin-top:50px;">
      <h3 style="float:right;"><a  href="studentHome.php">Back to Dashboard</a></h3>
      <h1>Test Results</h1>
      <table class="table">
        <thead>
          <tr>
            <th>Total Questions</th>
            <th>Correct Answers</th>
            <th>Wrong Answers</th>
            <th>Not Attempted</th>
            <th>Score</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><h1><?php echo $total; ?></h1></td>
            <td><h1><?php echo $right; ?></h1></td>
            <td><h1><?php echo $wrong; ?></h1></td>
            <td><h1><?php echo $no_attempt; ?></h1></td>
            <td><h1><?php echo number_format((float) $score,2, '.', '').' %'; ?></h1></td>
          </tr>
        </tbody>
      </table><br><br>
      <center><h3>Results for the descriptive type questions will be evaluated and displayed later!!!</h3></center>
    </div>
  </body>
</html>
