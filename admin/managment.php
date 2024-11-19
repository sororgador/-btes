<html>
<body>
<?php session_start();?>

<div class="form-container">
  <form class="form" method="post">
    <ul class="form-actions">
      <li>
        <button type="submit" name="event" class="btn btn-primary btn-large">managment events</button>
      </li>
      <li>
        <button type="submit" name="admin" class="btn btn-secondary btn-large">managment admin</button>
      </li>
	  <li>
        <button type="submit" name="user" class="btn btn-secondary btn-large">managment user</button>
      </li>
    </ul>
  </form>
</div>
<?php
if(isset($_POST['event']))
{
	header('LOCATION: /get/events/event_manage_form.php');	
}
if(isset($_POST['admin']))
{
	header('LOCATION: admin_managment.php');	
}
if(isset($_POST['user']))
{
	header('LOCATION: user_managment.php');	
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
  .form-container {
    padding: 20px;
  }

  .form {
    padding: 24px;
  }
}
</style>
</head>

 </html>
