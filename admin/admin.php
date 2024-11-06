<?php
include_once(connection.php);
include_once(users.php);
class Admin extends User {
public function check($conn)
{   
      $flag=0;  
      $u=$_POST['name'];   
      $p=$_POST['password']; 
      $type='admin';
    try{
	$sql="SELECT * FROM sign_in" ;
	$rows=$conn->query($sql);
	if($rows->rowCount() != 0)
	{
	while($row=$rows->fetch(PDO::FETCH_OBJ))
	  {
		if(($row->username == $u) && ($row->Password == $p ) && (&row->type == $type))
		{ 
	            header('LOCATION:  /get/admin/managment.php');// تعديل
			$flag=1;   break;	
		}
	   }
	}
	else 
	{ 
         echo "NO RECORD";
         }
	}
	catch(PDOException $e) 
	{
           echo "Error: " . $e->getMessage();
         }
  if($flag==0){ echo " ادخالك غير صحيح";}
}
//////////////////////////////////////////////
 public function formupdate($conn,$adminName,$adminEmail)
{?>
  <form action="#" method="post">
  name: <input type="text" name="name" value=<?php echo $adminName ?> >
  email: <input type="email" name="email" value=<?php echo $adminEmail ?> >
  <input type="submit" name="update1" value="update">
  </form>
  <?php
}
////////////////////////////////////
public function data_edit($conn)
{
	$adminId=$_SESSION['id']; 
	   try
    {
       $sql="SELECT admin_name , email FROM admin where admin_id='$adminId' ";
	   $rows=$conn->query($sql);
	   if($rows->rowCount() != 0)
	   {
       $row=$rows->fetch(PDO::FETCH_OBJ);
	        $adminName=$row->admin_name;
                $adminEmail=$row->email;
	       $this->formupdate($conn,$adminName,$adminEmail);
	   }
       else 
	   {
		   echo "not found";
	   }		   
    }
   catch(PDOException $e) 
   {
    echo "Error: " . $e->getMessage();
   }  
	
}
/////////////////////////////////////////////////////////
public function update($conn)
{
$adminId=$_SESSION['id'];
$adminName=$_POST['name'];
$adminEmail=$_POST['email'];
   try
    {
       $query="UPDATE admin SET admin_name='$adminName'
	   , email='$adminEmail' where admin_id='$adminId' ";
       $conn->exec($query);
       echo "Record update successfully";
    }
   catch(PDOException $e) 
   {
    echo "Error: " . $e->getMessage();
   }  
}
////////////////////////////////////////////////////////
public function formInsert($conn)
{?>
  <form action="#" method="post">
  name: <input type="text" name="name">
  email: <input type="email" name="email">
          <input type="submit" name="insert" value="insert">
  </form>
  <?php
}
////////////////////////////////////////////////////////
 public function validateFormInsert($conn) 
{
    $errors = [];

    // التحقق مما إذا كانت البيانات قد أُرسلت عبر POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // التحقق من وجود الاسم
        if (empty($_POST["name"])) {
            $errors[] = "الاسم مطلوب.";
        }

        // التحقق من وجود البريد الإلكتروني
        if (empty($_POST["email"])) {
            $errors[] = "البريد الإلكتروني مطلوب.";
        } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "البريد الإلكتروني غير صالح.";
        }
    }

    return $errors;
}
////////////////////////////////////////////////////////////
public function callvalidateFormInsert($conn)
{
  
 // استدعاء دالة التحقق من الصحة
    $errors = $this->validateFormInsert($conn);

    // إذا كانت البيانات صحيحة، يمكنك إدخالها في قاعدة البيانات
    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors)) {
       
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);

        echo "<p style='color:green;'>تمت إضافة البيانات بنجاح!</p>";
    }

    // عرض الأخطاء إذا كانت موجودة
    if (!empty($errors)) 
  {
        foreach ($errors as $error)
    {
            echo "<p style='color:red;'>$error</p>";
        }
       $errors = $this->formInsert($conn);
    }
}
////////////////////////////
public function insert($conn)
{
 
$adminName=$_POST['name'];
$adminEmail=$_POST['email'];
 
try{  
  
  $sql="INSERT INTO admin(admin_name,email) values ('$adminName','$adminEmail')";
  $conn->exec($sql);
    echo "Record insert successfully "; 
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
}// end function
///////////////////////////////////////////////////////
public function checkRecord($conn)
{
  $adminId=$_POST['id'];
  try{
  $sql="SELECT * FROM admin where admin_id = '$adminId' ";
  $rows=$conn->query($sql);
  $n=$rows->rowCount();
  
  if($n == 0)
  {
    echo "no record to delete";
  }
  else{
    $this->deleteadmin($conn);
  }
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
  $conn = null;
  
}
///////////////////////////////////////////////////
public function deleteadmin($conn)
{
  $adminId=$_POST['id'];
  try{
  $sql="delete from admin where admin_id = '$adminId'";
    $conn->exec($sql);
    echo "delete is done";
  }
  catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
  $conn = null;
  
}
/////////////////////////////////////////////////
  public function displayformsearch($conn)
{
?>
   <form action="#" method="post">
   search for admin: <input type="text" name="id">
   <input type="submit" name="search">
   </form>
  <?php 
}
}///end class 
// إنشاء كائن من كلاس Admin
$admin = new Admin("AdminUser", 2, "admin@example.com", "adminPass");
