<html>
<body>
<div class="form-container">
  <form class="form" method="post">
    <ul class="form-actions">
      <li>
        <button type="submit" name="kid" class="btn btn-secondary btn-large">اطفال</button>
      </li>
      <li>
        <button type="submit" name="teen" class="btn btn-danger btn-large">مراهقون</button>
      </li>
	    <li>
        <button type="submit" name="adult" class="btn btn-danger btn-large">بالغين</button>
      </li>
	      <li>
        <button type="submit" name="eld" class="btn btn-danger btn-large"> كبار السن(المتقاعدون)</button>
      </li>
    </ul>
  </form>
</div>
<?php
session_start();
if(isset($_POST['kid']))
{      
     $_SESSION['ranged']=0;
	  $_SESSION['rangeu']=12;
	header('LOCATION: view_customers_age.php');	
}
if(isset($_POST['teen']))
{
      $_SESSION['ranged']=13;
	  $_SESSION['rangeu']=17;
	header('LOCATION: view_customers_age.php');		 
}
if(isset($_POST['adult']))
{
      $_SESSION['ranged']=18;
	  $_SESSION['rangeu']=64;
	header('LOCATION: view_customers_age.php');		
}
if(isset($_POST['eld']))
{
	    $_SESSION['ranged']=65;
	  $_SESSION['rangeu']=100;
	header('LOCATION: view_customers_age.php');	
}

 

?>

 </body>
 <head>
<style>
/* Form Container */
.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: #f0f0f0; /* Softer background color */
}

/* Form */
.form {
  background-color: #fff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

/* Form Actions */
.form-actions {
  list-style-type: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-actions li {
  width: 100%;
}

/* Buttons */
.btn {
  display: block;
  width: 100%;
  padding: 12px 20px;
  font-size: 16px;
  font-weight: bold;
  border-radius: 4px;
  transition: background-color 0.3s ease;
  cursor: pointer;
}

.btn-primary {
  background-color: #2196F3; /* Blue */
  color: #fff;
}

.btn-primary:hover {
  background-color: #0D47A1; /* Darker blue */
}

.btn-secondary {
  background-color: #2196F3;  /* Gray */
  color: #fff;
}

.btn-secondary:hover {
  background-color: #616161; /* Darker gray */
}

.btn-danger {
  background-color: #2196F3;  
  color: #fff;
}

.btn-danger:hover {
  background-color: #B71C1C; /* Darker red */
}

/* Responsive Styles */
@media (max-width: 767px) {
  .form-container 
  {
    padding: 20px;
  }

  .form 
       {
    padding: 24px;
        }
}
</style>
</head>
 </html>