<?php
include_once("connection.php");
function displayformsearch1($conn)
{
?>
   <form action="#" method="post">
   search: <input type="text" name="id">
   <input type="submit" name="search">
   </form>
  <?php 
}
if(isset($_POST['search']))
{
$_SESSION['id']=$_POST['id'];
}
  
?>