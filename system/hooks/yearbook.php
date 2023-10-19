<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');  global $userID; global $LANG; global $server; $school_id = $_SESSION['school_id']; 
$page = 1; $setLimit = 30;
if(isset($_GET["page"])) $page = (int)$_GET["page"];
$pageLimit = ($page * $setLimit) - $setLimit;

/*
File name: 		yearbook.php
Description:	This is the parent page
Developer: 		Ynet Interactive
Date: 			3/02/2015
*/
global $server;
$Currency = new DefaultCurrency();
$userRate = $Currency->Rate(getUser());
$userSymbul = $Currency->Symbul(getUser());

	$currentSession = getSetting('current_session');
	$currentTerm = getSetting('current_term');

?>

<?php
if(userRole($userID) > 3 && userRole($userID) != 4) {
header('location: admin.php');
}

	$session = getSetting('current_session');
	$term = getSetting('current_term');
	$class="0";

if (getSetting('current_session') < 1) {
$message = 'You have not defined the current accademic session yet!. <br>You must fix this before you can manage fees. ';
	if(userRole($userID) <3) {
	$message .= '<a href="admin/generalsetting" title="Difine Active Session">Click Here</a> to fix it now.';
	} else {
	$message .= 'Consult your administrator for assistance';
	}
$class='yellow';
}


if(isset($_GET['class_id']))
{
$session = filterinp($_GET['session_id']);
$class = filterinp($_GET['class_id']);

	$sql=$query = "select * FROM student_class sc JOIN students s ON sc.student_id = s.id WHERE class_id = '$class' AND session_id = '$session' ORDER BY s.first_name ASC";
	$report_title = sessionName($session).' '.className($class).' Year Book';
 	$resultP = mysqli_query($server, $sql) or die(mysqli_error($server));
	$numP = mysqli_num_rows($resultP);


	if($numP < "1")
		{
		$message = "No record fund for your selections! Please try another search.";
		$class="blue";
		}
} else {

		$message = "Select desired session and class to view year book";
		$class="blue";
}

?>

<div class="wrapper">
    	<div id="mess" style="position: relative; top: 0;">
            <?php if(!empty($message)) { showMessage($message,$class); } ?>
        </div>
	<div id="search-pan">
    	<form action="" method="get">
        <select name="session_id" id="e1">
       		<option value="0">Select Session</option>
			<?php
			     $sqlC=$queryC="SELECT * FROM sessions WHERE school_id = '$school_id' ORDER BY id DESC";
                $resultC = mysqli_query($server, $queryC);
                $numC = mysqli_num_rows($resultC);

				while($rowC=mysqli_fetch_assoc($resultC)){
                $c_id = $rowC['id'];
                $titleF = $rowC['title'];
            ?>
               <option value="<?php echo $c_id; ?>" ><?php echo $titleF; ?></option>
            <?php }  ?>
			</select>
        &nbsp;
        <select name="class_id" id="e3"  >
        <option  value="<?php echo '999999'; ?>"><?php echo 'Graduate Class'; ?></option>
			<?php
			     $sqlC=$queryC="SELECT * FROM classes WHERE school_id = '$school_id' ORDER BY title ASC";
                $resultC = mysqli_query($server, $queryC);
                $numC = mysqli_num_rows($resultC);

                $iC=0;
				while($rowC=mysqli_fetch_assoc($resultC)){
                $c_id = $rowC['id'];
                $title = $rowC['title'];
            ?>
               <option  value="<?php echo $c_id; ?>"><?php echo $title; ?></option>
            <?php  $iC++; }  ?>
		</select>
        &nbsp;
        <button class="submit"><i class="fa fa-search"></i> View <hide>Yearbook</hide></button>
        <a href="" onClick="javascript:printDiv('print-this1')"><button class="submit">Print <hide>Yearbook</hide></button></a>
        </form>
    </div>

    <?php if(isset($_GET['class_id'])) { ?>
	<div class="panel" id="print-this1" style="/* [disabled]border-color: transparent; */">
    	<div class="panel-head"> &nbsp;<?php echo $report_title;?></div>
        <div class="panel-body">

               <?php
				$iP=0;
 				while($rowP=mysqli_fetch_assoc($resultP)){
					$student = $rowP['student_id'];
					$sql=$query="SELECT * FROM students WHERE id = '$student'";
					$result = mysqli_query($server, $query) or die(mysqli_error($server));
					$row = mysqli_fetch_assoc($result);
					$picture = $row['photo'];
					$name = studentName($row['id']);
					$sex = $row['sex'];
					if(empty($picture)) {
						$picture = 'no-body.png';
					}

				?>
             <div class="virtualpage hidepeice">
				<div class="yearbook">
                	<img src="media/uploads/<?php echo $picture; ?>" /><br />
                    <?php echo $name; ?><br /><?php echo $sex; ?>
                </div>

              </div>
              <?php $iP++; } ?>

    <?php } ?>

<?php if(@$numP>1) displayPagination($setLimit,$page,$sql) ?>

        </div>
    </div>
</div>