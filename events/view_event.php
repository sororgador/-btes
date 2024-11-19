<?php
 
include_once("connection.php");
include_once("class_event.php");
 if(!isset($_POST['search']))
 {
	 $event->displayformsearch($conn);
 }

  else if(isset($_POST['search']))  
	{
		 $event->search($conn);
		 
    }

echo $event->displayEvent($conn);
?>
  <form  action="/get/admin/login_review.php" method="POST">
  <input type="submit" name="add" value="Add review">
  </form>
  <?php
 
?>
