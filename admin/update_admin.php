<?php
include_once("connection.php");
include_once("users.php");
include_once("search_admin.php");
if(isset($_POST['update1']))
  {
  echo $admin->update($conn); 
  }
 else if(isset($_POST['search']))
  {
   echo $admin->data_edit($conn);
  }
  else
  {
      displayformsearch1($conn);
  }
   