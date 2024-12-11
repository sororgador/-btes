<?php
include_once("connection.php");

?>
<html>
 <body>

<div class="form-container">
<form class="form" method="post">
    <ul class="form-actions">
      <li>
        <button type="submit" name="add" class="btn btn-secondary btn-large">اضافة احداث الى القائمة</button>
      </li>
	     <li>
        <button type="submit" name="view" class="btn btn-secondary btn-large">عرض قائمتك المفضلة</button>
      </li>
	  <li>
        <button type="submit" name="delete" class="btn btn-secondary btn-large"> حذف حدث من القائمة</button>
      </li>
    </ul>
  </form>
</div>
</body>
 
<?php
if(isset($_POST['add']))
{
	header('LOCATION: create_fav.php');	
}
if(isset($_POST['view']))
{
	header('LOCATION: view_fav.php');	
}
if(isset($_POST['delete']))
{
	header('LOCATION: delete_fav.php');	
}
?> 
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج الأزرار</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* لجعل العناصر تتراص عموديًا */
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center; /* لتوسيع الأزرار بشكل مناسب */
        }
        .form-actions {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .form-actions li {
            margin: 10px 0;
        }
        .btn {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
            margin-bottom: 20px; /* مسافة تحت زر ADMIN */
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

</html>