<?php require'database.php' ?>
<?php
  session_start();
  if(!isset($_SESSION['user_id'])){
  	header("Location: index.php");
  }
  $test_id = $_GET['var'];
  //session_start();
  $_SESSION['test_id'] = $test_id;
  $query = mysqli_query($conn,"SELECT * FROM test WHERE test_id='$test_id' ");
  $result = mysqli_fetch_array($query);
  $test_name=$result['test_name'];
  $test_duration=$result['test_duration'];
  $q_query=mysqli_query($conn,"SELECT * FROM questions WHERE test_id='$test_id' ");
  $q_result=mysqli_fetch_all($q_query, MYSQLI_ASSOC);
  $desc_query=mysqli_query($conn,"SELECT * FROM questions_desc WHERE test_id='$test_id' ");
  $desc_result=mysqli_fetch_all($desc_query, MYSQLI_ASSOC);
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
      $('form').data('serialize',$('form').serialize()); // On load save form current state
      $(window).bind('beforeunload', function(e){
          if($('form').serialize()!=$('form').data('serialize'))return true;
          else e=null; // i.e; if form state change show warning box, else don't show it.
      });
    </script>
	</head>
  <body onLoad="timeout()">
    <div class="container">
    	<script type="text/javascript">
    	var timeLeft = 60 * <?php echo $test_duration; ?>;
    	</script><br>
    	<div class="row" style="border:1px solid black;">
    		<div class="col-lg-8"><h1><?php echo $test_name; ?></h1></div>
    		<div class="col-lg-2"><h2 id="time" style="float:right";></h2></div>
    		<div class="col-lg-2"><h2>Minutes Left</h2></div>
    	</div><br>
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" data-target="#mcq">Multiple Choice Questions</a></li>
        <li><a data-toggle="tab" data-target="#desc">Descriptive Type Questions</a></li>
      </ul>
      <form id="quiz" method="post" action="showResult.php">
        <div class="tab-content">
          <div id="mcq" class="tab-pane in fade active">
          	<?php foreach ($q_result as $q_res): ?>
          	<table class="table table-striped">
          	    <thead>
          	      <tr>
          	        <th><?php echo $q_res['question'] ?></th>
          	      </tr>
          	    </thead>
          	    <tbody>
                  <tr>
                    <?php
                      if(strlen($q_res['image'])>10){ ?>
                        <img src="<?php echo $q_res['image'] ?>" style="max-height:300px;max-width:400px;"></img><br>
                    <?php  }?>
                  </tr>
          	      <tr>
          	        <td><input type="radio" value="a" name="<?php echo $q_res['question_id'] ?>">
          	        <?php echo $q_res['option_a'] ?></td>
          	      </tr>
          	      <tr>
          	        <td><input type="radio" value="b" name="<?php echo $q_res['question_id'] ?>">
          	        <?php echo $q_res['option_b'] ?></td>
          	      </tr>
          	      <tr>
          	        <td><input type="radio" value="c" name="<?php echo $q_res['question_id'] ?>">
          	        <?php echo $q_res['option_c'] ?></td>
          	      </tr>
          	      <tr>
          	        <td><input type="radio" value="d" name="<?php echo $q_res['question_id'] ?>">
          	        <?php echo $q_res['option_d'] ?></td>
                  </tr>
                  <tr>
          	        <td><input type="radio" hidden checked="checked" value="none" name="<?php echo $q_res['question_id'] ?>">
          	        </td>
          	      </tr>
          	    </tbody>
            	</table>
            	<hr>
          	<?php endforeach; ?>
          </div>
          <div id="desc" class="tab-pane fade">
            <?php foreach ($desc_result as $desc_res): ?>
              <h2><?php echo $desc_res['question'] ?></h2>
              <?php
                if(strlen($desc_res['image'])>10){ ?>
                  <img src="<?php echo $desc_res['image'] ?>" style="max-height:300px;max-width:400px;"></img><br>
              <?php  }?>
              <textarea class="form-control" name="<?php echo $desc_res['question_id'] ?>" placeholder="Write you code or answer here"></textarea>
              <hr>
            <?php endforeach; ?>
          </div>
        </div>
        <center>
          <input type="submit" name="submitTest" value="Submit Test" class="btn btn-danger" style="height: 40px; width:300px;">
        </center>
      </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  </body>
</html>
