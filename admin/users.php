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

class Customer extends User {
    private $customerName;
    private $phoneNumber;
    private $customerAge;
    private $customerQualification;

    public function __construct($userName, $userId, $userEmail, $userPassword, 
                                $customerName, $phoneNumber, $customerAge, $customerQualification) {
        parent::__construct($userName, $userId, $userEmail, $userPassword);
        $this->customerName = $customerName;
        $this->phoneNumber = $phoneNumber;
        $this->customerAge = $customerAge;
        $this->customerQualification = $customerQualification;
    }
public function register_form()
{
	?>
	 <div class="form">
	      <h2>create an account</h2>
    <form method="post" action="#">
      <label for="name">Username:</label>
      <input type="text" id="name" name="name">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password">
	  <label for="password">Password again:</label>
      <input type="password" id="password" name="password2">
      <input type="submit" name="submit" value="submit">
	 
    </form>
  </div>
	<?php

}
////////////end fun regester_form/////////
public function validateRegistrationForm($username, $password, $password2) 
{
    $errors = []; // مصفوفة لتخزين الأخطاء

    // تحقق من اسم المستخدم
    if (empty($username)) {
        $errors[] = "اسم المستخدم مطلوب.";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $errors[] = "اسم المستخدم يجب أن يكون بين 3 و 20 حرفًا.";
    }

    // تحقق من كلمة المرور
    if (empty($password)) {
        $errors[] = "كلمة المرور مطلوبة.";
    } elseif (strlen($password) < 6) {
        $errors[] = "كلمة المرور يجب أن تكون على الأقل 6 أحرف.";
    }

    // تحقق من تطابق كلمتي المرور
    if ($password !== $password2) {
        $errors[] = "كلمتا المرور غير متطابقتين.";
    }

    return $errors; // إرجاع الأخطاء
}
///////////end fun validateRegistrationForm/////////
public function callvalidateregister($conn)
{
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // استعادة المدخلات
        $username = $_POST['name'] ?? '';
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        // التحقق من المدخلات
        $errors = $this->validateRegistrationForm($username, $password, $password2);

        // إذا لم تكن هناك أخطاء، يمكنك متابعة التسجيل
        if (empty($errors)) {
            // هنا يمكنك تنفيذ عملية التسجيل في قاعدة البيانات
            // مثال: 
            // $query = "INSERT INTO users (username, password) VALUES (?, ?)";
            // استخدم الـ $conn لإجراء العملية

            echo "<p style='color:green;'>تم إنشاء الحساب بنجاح!</p>";
        } else {
            // عرض الأخطاء
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
      
        }
   echo $this->register_form();
  }
}
/////////////////////////////////////////////
public function register($conn)
{
 
$userName=$_POST['name'];
$userPassword=$_POST['password'];
$type="customer";
 
try{  
  
  $sql="INSERT INTO sign_in(username,password,type) values ('$userName','$userPassword','$type')";
  $conn->exec($sql);
    echo "Record insert successfully "; 
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
}// end function
/////////////////////////////////////////////
public function check($conn)
{   
      $flag=0;  
	  $u=$_POST['name'];   $p=$_POST['password'];
    try{
	$sql="SELECT * FROM sign_in" ;
	$rows=$conn->query($sql);
	if($rows->rowCount != 0)
	{
	while($row=$rows->fetch(PDO::FETCH_OBJ))
	{
		if(($row->username == $u) && ($row->Password == $p))
		{ 
	       header('LOCATION:  /get/ /.php');// تعديل
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
////////////////////////////////////////////////////
function formInsertCustomer($conn)
{?>
  <form action="#" method="post">
  name: <input type="text" name="name"><br>
  email: <input type="email" name="email"><br>
  phone: <input type="text" name="number"><br>
  age: <input type="text" name="age"><br>
  qualification:<br>
  <input type="radio" name="qualification" value="student">student<br>
  <input type="radio" name="qualification" value="malitary">malitary<br>
  <input type="radio" name="qualification" value="teacher">teacher<br>
  <input type="radio" name="qualification" value="other">other<br>
  <input type="submit" name="submit" value="submit">
  </form>
  <?php
}
////////////////////////////////////////////////
public function validateFormInsert($name, $email, $phone, $age, $qualification) 
{
    $errors = []; // مصفوفة لتخزين الأخطاء

    // تحقق من الاسم
    if (empty($name)) {
        $errors[] = "الاسم مطلوب.";
    }

    // تحقق من البريد الإلكتروني
    if (empty($email)) {
        $errors[] = "البريد الإلكتروني مطلوب.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "البريد الإلكتروني غير صالح.";
    }

    // تحقق من رقم الهاتف
    if (empty($phone)) {
        $errors[] = "رقم الهاتف مطلوب.";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = "رقم الهاتف يجب أن يكون مكونًا من 10 أرقام.";
    }

    // تحقق من العمر
    if (empty($age)) {
        $errors[] = "العمر مطلوب.";
    } elseif (!is_numeric($age) || $age <= 0) {
        $errors[] = "العمر يجب أن يكون رقمًا موجبًا.";
    }

    // تحقق من المؤهل
    if (empty($qualification)) {
        $errors[] = "يجب اختيار المؤهل.";
    }

    return $errors; // إرجاع الأخطاء
}
//////////end fun validateFormInsert/////////
public function clallvlidatinsert($conn)
{
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // استعادة المدخلات
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['number'] ?? '';
        $age = $_POST['age'] ?? '';
        $qualification = $_POST['qualification'] ?? '';

        // التحقق من المدخلات
        $errors = $this->validateFormInsert($name, $email, $phone, $age, $qualification);

        // إذا لم تكن هناك أخطاء، يمكنك متابعة الإدخال في قاعدة البيانات
        if (empty($errors)) {
            // هنا يمكنك تنفيذ عملية الإدخال في قاعدة البيانات
            // مثال: 
            // $query = "INSERT INTO users (name, email, phone, age, qualification) VALUES (?, ?, ?, ?, ?)";
            // استخدم الـ $conn لإجراء العملية

            echo "<p style='color:green;'>تم إدخال البيانات بنجاح!</p>";
        } else {
            // عرض الأخطاء
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
       
    }
}
}
/////////end fun clallvlidatinsert////////
public function insertcustomer($conn)
{
 
$customerName=$_POST['name'];
$customerEmail=$_POST['email'];
$customerPhone= $_POST['number'];
$customerAge=$_POST['age'];
$customerQualification=$_POST['qualification'];
try{  
  
  $sql="INSERT INTO customers( customer_name,age,qualification,phone_num,email) values ('$customerName','$customerAge','$customerQualification','$customerPhone','$customerEmail')";
  $conn->exec($sql);
    echo "Record insert successfully "; 
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
}// end function

}//////end class

// إنشاء كائن من كلاس Customer
$customer = new Customer("JohnDoe", 1, "john@example.com", "password123", 
                         "John Doe", "1234567890", 30, "Bachelor's");


 

?>
