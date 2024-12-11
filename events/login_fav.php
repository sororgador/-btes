<?php 
session_start();
ob_start();
include_once("connection.php");
include_once("class_fav.php"); 
 function formLoginFav()//دالة بها نموذج لتسجل دخول المستخدم عند اضافة تقييم
{?>
	  <div class="form">
	      <h2>login</h2>
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

   if (isset($_POST['login'])) {
    $username = $_POST['name'];
    $password = $_POST['password'];

    // تحقق من صحة بيانات الدخول
    $sql = "SELECT * FROM sign_in WHERE username = :username AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
       // تأكد من بدء الجلسة
        $_SESSION['username'] = $username;// تخزين اسم المستخدم في الجلسة
        header('Location: /get/events/fav1.php'); // إعادة التوجيه بعد تسجيل الدخول
		exit;
    } else {
        echo "بيانات الدخول غير صحيحة.";
    }
  }
else{
     formLoginFav();
	}
	
?>
 <html>
<head>
<style>
    .form {
      background-color: white;
      border: 1px solid #007bff;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 30px;
      margin: 50px auto;
      max-width: 400px;
    }
 
	      body {
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }
	    .form h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .form input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-bottom: 15px;
    }

    .form input[type="submit"] {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
    }

    .form input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .form a {
      display: block;
      text-align: center;
      color: #007bff;
      text-decoration: none;
      margin-top: 10px;
    }

    .form a:hover {
      text-decoration: underline;
    }
</style>
</head>
</html>
