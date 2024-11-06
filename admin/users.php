<?php
include_once("connection.php");
@session_start();
class User {
    protected $userName;
    protected $userId;
    protected $userEmail;
    protected $userPassword;

    public function __construct($userName, $userId, $userEmail, $userPassword) {
        $this->userName = $userName;
        $this->userId = $userId;
        $this->userEmail = $userEmail;
        $this->userPassword = $userPassword;
    }
/////////////////////////////////////////////////////
 public function formLogin()
{?>
	  <div class="form">
	      <h2>login</h2>
      <form method="post" action="#">
      <label for="name">Username:</label>
      <input type="text" id="name" name="name">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
      <input type="submit" name="login" value="LOGIN">
	  <h2><a href="login_edit.php">تعديل الملف الشخصي</a> </h2>
    </form>
  </div>
	<?php
}
////////////////////////////////////////////////
public function validateForm($username, $password) 
{
 

    // تحقق من username
    if (empty($username)) {
        echo "اسم المستخدم مطلوب.";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
     echo "اسم المستخدم يجب أن يكون بين 3 و 20 حرفًا.";
    }

    // تحقق من password
    if (empty($password)) {
      echo "كلمة المرور مطلوبة.";
    } elseif (strlen($password) < 6) {
       echo "كلمة المرور يجب أن تكون على الأقل 6 أحرف.";
    } 
}

///////////////////////////////////////////////////////////////////
public function form1()
{ 
{?>
  <div class="form">
	    <h2>الرجاء ادخال بياناتك السابقة</h2>
    <form method="post" action="#">
      <label for="name">Username:</label>
      <input type="text" id="name" name="name">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
      <input type="submit" name="login" value="LOGIN">
	  
    </form>
  </div>
	<?php
}
}
////////////////////////////////////////////////////
public function validateForm1($name, $password)
{
    $errors = [];

    // التحقق من وجود الاسم
    if (empty($name)) {
        $errors[] = "الاسم مطلوب.";
    }

    // التحقق من وجود كلمة المرور
    if (empty($password)) {
        $errors[] = "كلمة المرور مطلوبة.";
    } elseif (strlen($password) < 6) {
        $errors[] = "يجب أن تتكون كلمة المرور من 6 أحرف على الأقل.";
    }

    return $errors;
}
///////////////////end fun validateForm ////////
public function callvalidateForm($conn)
{
  $errors = $this->validateForm($name, $password);
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($errors))
  {
      
        echo "<p style='color:green;'>تمت إضافة البيانات بنجاح!</p>";
    }
    
    if (!empty($errors)) 
  {
        foreach ($errors as $error)
    {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    
}
/////////////////////////////////////////////////////
function checkToUpdate($conn)
{   
      $flag=0;  
	  $u=$_POST['name'];
	  $p=$_POST['password'];
    try{
	$sql="SELECT * FROM sign_in" ;
	$rows=$conn->query($sql);
	while($row=$rows->fetch(PDO::FETCH_OBJ))
	{
		if(($row->username == $u) && ($row->Password == $p))
		{ 
	       header('LOCATION:  update_login.php');
			$flag=1;
			break;
		}
		
	}
	}
	catch(PDOException $e) 
   {
	
    echo "Error: " . $e->getMessage();
     
   }
  	   
  if($flag==0){ echo " ادخالك غير صحيح";}
	
}
///////////////////////////////////////////////////////////// 
public function formUpdateLogin($conn)
{ ?>
  <form action="#" method="post">
  name: <input type="text" name="name">
  password: <input type="text" name="password">
  <input type="submit" name="update1" value="update">
  </form>
  <?php
}
public function updatelogin($conn)
{
$user=$_SESSION['username'];
$pass=$_SESSION['password'];
$adminName=$_POST['name'];
$adminPassword=$_POST['password'];
 
   try
      {
        $query="UPDATE sign_in SET username='$adminName', password='$adminPassword' 
		where username='$user' and password='$pass' ";
        $conn->exec($query);
        echo "Record update successfully";
      }
   catch(PDOException $e) 
     {
       echo "Error: " . $e->getMessage();
     }
  
 }
}/////end class
?>
