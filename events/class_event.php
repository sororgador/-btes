<?php
 
include_once("connection.php");
include_once("class_seats.php");
class Event {
    private $eventId;
    private $title;
    private $date;
    private $time;
    private $location;
    private $description;
    private $category;
    private $numOfSeats;
    private $numOfSeatType;
    private $seatsList; // كائن من نوع Seats
    private $ticketList; // كائن من نوع Ticket
    private $reviewList; // كائن من نوع Review
    private $performance; // قائمة بأسماء الممثلين

    public function __construct($eventId, $title, $date, $time, $location, $description, $category, $numOfSeats, $numOfSeatType)
	{
        $this->eventId = $eventId;
        $this->title = $title;
        $this->date = $date;
        $this->time = $time;
        $this->location = $location;
        $this->description = $description;
        $this->category = $category;
        $this->numOfSeats = $numOfSeats;
        $this->numOfSeatType = $numOfSeatType;
        $this->seatsList = []; // مصفوفة أو كائن Seats سيتم تعريفه لاحقًا
        $this->ticketList = []; // مصفوفة أو كائن Ticket سيتم تعريفه لاحقًا
        $this->reviewList = []; // مصفوفة أو كائن Review سيتم تعريفه لاحقًا
        $this->performance = []; // قائمة بأسماء الممثلين
    }
	///////////////////////////////////////////
 public function addSeat($seatId,$priceSeat,$status,$eventId)
    {
        $seat = new Seats($seatId,$priceSeat,$status,$eventId);
        $this->seatsList[] = $seat;
    }
///////////////////////////////////////fun display لعرض الاحداث على المستخدم
public function displayEvent($conn)
{
    $currentDateTime = date('Y-m-d H:i:s');

    try {
        $sql = "SELECT * FROM events WHERE CONCAT(event_date, ' ', event_time) >= :currentDateTime";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':currentDateTime', $currentDateTime);
        $stmt->execute();
        
        while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
            echo '<div class="event">';
            echo '<h3>' . htmlspecialchars($row->title) . '</h3>';
            echo 'التاريخ: ' . htmlspecialchars($row->event_date) . '<br>';
            echo 'الوقت: ' . htmlspecialchars($row->event_time) . '<br>';
            echo 'المكان: ' . htmlspecialchars($row->location) . '<br>';
            echo 'عدد المقاعد: ' . htmlspecialchars($row->seats_number) . '<br>';
            echo 'الوصف: ' . htmlspecialchars($row->description) . '<br>';
            echo '<hr>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
//////////////////////////////////////
	///////displaywithreveiw//////////
  
public function displayEventWithReviews($conn)//دالة لعرض الاحداث مع امكانية القييم لكل حدث
{       try {
        $sql = "SELECT * FROM events";//احضار الاحداث
        $rows = $conn->query($sql);
        while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
            echo $row->title . "<br>" . $row->event_date . "<br>" . $row->event_time . "<br>" . $row->location . "<br>" 
                 . $row->seats_number . "<br>" . $row->description . "<br>";
            echo '<div class="event">';
            // نموذج إضافة التقييم
            echo '<form method="POST" action="#">
                    <label for="rating">تقييم الحدث:</label>
                    <select name="rating" id="rating">
		        <option></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
		    <label for="comment">التعليق:</label>
                    <textarea name="comment" id="comment" rows="4" cols="50"></textarea>
                    <input type="hidden" name="event_id" value="' . $row->event_id . '">
                    <input type="submit" value="تقييم" name="enter">
                </form>';
            // نموذج عرض التقييمات
            echo '<form method="POST" action="#">
                    <input type="hidden" name="id" value="' . $row->event_id . '">
                    <input type="submit" value="عرض تقييمات" name="ok">
                </form>';
            echo "<hr>";
            echo '</div>';
		}}
     catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
/////// end displaywithreveiw//////////
public function display($conn)
{?>
  <form method="post" action="#">
     <select name="event">
         <option></option>
           <?php $this->fillIn($conn);?>
     </select>
    <input type="submit" name="select" value="select event">
  </form>
	<?php
	
}
////////////////////////////////// لتعبئة البيانات منقاعدة البيانات داخل قائمة الاختيار
public function fillIn($conn)
{
	  try
      {
        $sql="SELECT * FROM events";
        $rows=$conn->query($sql);
      if($rows->rowCount() != 0)
	  {	 
        while($row=$rows->fetch(PDO::FETCH_OBJ))
         {   
             ?> <option value="<?php echo $row->event_id;?>"/>
			 <?php echo $row->event_id , "      "  ,$row->title , "     "  , $row->event_date;?></option><?php 
         }
       }
	   else
	   {  
         echo "NO RECORDS";
	   }
	  }
     catch(PDOException $e)
     {
     echo $sql . "<br>" . $e->getMessage();
     }
  

}//////////////////////////////////////////////////////////
public function displayformsearch($conn) 
{
?>
   <form action="#" method="post">
       <label for="title">البحث: </label>
       <input type="text" name="title" id="title" required>
       <input type="submit" name="search" value="بحث">
   </form>
<?php 
}

/////////////////////////////////////////////////////////// لتعبئة بيانات الحدث 
   public function display_form($conn)
{
    ?>
    <form method="post" action="#">
        <h2>Insert into events:</h2>
        Event ID: <input type="text" name="eventId"/><br>
        Title: <input type="text" name="title"/><br>
        Event Date: <input type="date" name="eventDate"/><br>
        Event Time: <input type="time" name="eventTime"/><br>
        Location: <input type="text" name="location"/><br>
        Number of Seats: <input type="text" name="numOfSeats"><br>
        Description: <textarea name="description"></textarea><br>
        <h3>Seats Information:</h3>
        <div id="seatsInfo"></div>
        <button type="button" onclick="addSeat()">Add Seat</button><br>
        <input name="ok" type="submit" value="Insert"/>
    </form>
    <script>
        function addSeat() {
            const seatInfoDiv = document.getElementById('seatsInfo');
            const seatCount = seatInfoDiv.children.length;
            const seatHTML = `
                <div>
                    Seat ID: <input type="text" name="seatId[${seatCount}]"/>
                    Status: <input type="text" name="status[${seatCount}]"/>
                    Price: <input type="text" name="price[${seatCount}]"/>
                    <button type="button" onclick="this.parentElement.remove()">Remove</button>
                </div>`;
            seatInfoDiv.insertAdjacentHTML('beforeend', seatHTML);
        }
    </script>
    <?php
}
 
///////////////////////////////////////////////////////////////
 public function validationdisplay_form($conn)
{
    $errors = [];

    // تحقق من وجود عنوان
    if (empty($_POST['title'])) {
        $errors['title'] = "Title is required.";
    }

    // تحقق من وجود تاريخ الحدث
    if (empty($_POST['eventDate'])) {
        $errors['eventDate'] = "Event date is required.";
    }

    // تحقق من وجود وقت الحدث
    if (empty($_POST['eventTime'])) {
        $errors['eventTime'] = "Event time is required.";
    }

    // تحقق من وجود موقع
    if (empty($_POST['location'])) {
        $errors['location'] = "Location is required.";
    }

    // تحقق من عدد المقاعد
    if (empty($_POST['numOfSeats']) || !is_numeric($_POST['numOfSeats'])) {
        $errors['numOfSeats'] = "Number of seats must be a valid number.";
    }

    // تحقق من الوصف
    if (empty($_POST['description'])) {
        $errors['description'] = "Description is required.";
    }

    // تحقق من معلومات المقاعد
    if (isset($_POST['seatId'])) {
        foreach ($_POST['seatId'] as $index => $seatId) {
            if (empty($seatId)) {
                $errors["seatId[$index]"] = "Seat ID is required.";
            }
        }
  
  }

    return $errors;
}
/////////end validationdisplay_form///////////
public function callvalidationdisplay_form($conn)
{
    // إذا كان المستخدم قد أرسل البيانات من النموذج
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // التحقق من المدخلات
        $errors = $this->validationdisplay_form($conn);

        // إذا كانت المدخلات صحيحة (أي لا توجد أخطاء)
        if (empty($errors)) {
            // إذا كانت المدخلات سليمة، نقوم بإضافة البيانات إلى قاعدة البيانات
            $this->addEvent($conn);
            echo "<p style='color:green;'>تم إضافة الحدث بنجاح!</p>";
        } else {
            // عرض الأخطاء للمستخدم إذا كانت المدخلات غير صحيحة
            foreach ($errors as $error) {
                echo "<p style='color:red;'>$error</p>";
            }
       $this->display_form($conn);
        }
    }
}
//////////////// callvalidationdisplay_form /////////
//----------------- function insert------------------------------------------
public function addEvent($conn)
{	
$eventId=$_POST['eventId'];
$title=$_POST['title'];
$date=$_POST['eventDate'];
$time=$_POST['eventTime'];
$location=$_POST['location'];
 
$numOfSeats=$_POST['numOfSeats'];
$description=$_POST['description'];
 
 
try{	
	
	$sql="INSERT INTO events(event_id,title,event_date,event_time,location,seats_number,description)
	values( '$eventId' ,'$title', '$date' , '$time' ,'$location','$numOfSeats','$description')";
	$conn->exec($sql);

    echo "Record insert successfully "; 
	$this->addSeatsToDatabase($conn, $eventId);
}

catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
}
/////////
    private function addSeatsToDatabase($conn, $eventId)
    {
        if (isset($_POST['seatId'])) {
            $seatIds = $_POST['seatId'];
            $statuses = $_POST['status'];
            $prices = $_POST['price'];

            foreach ($seatIds as $index => $seatId) {
                $status = $statuses[$index];
                $price = $prices[$index];

                // إدخال المقاعد في قاعدة البيانات
                $sql = "INSERT INTO seats(seat_id, status,event_id, price) VALUES ('$seatId', '$status', '$eventId', '$price')";
                $conn->exec($sql);
            }
            echo "Seats inserted successfully";
        }
    }
//// 6////////////////////////////////////////////////////////////
 
//----------------------------------delete-----------------------------//
public function cancelEvent($conn) 
{
        
    $dl=$_POST['event'];
		 
	try
       {
	      $sql="delete from events where event_id= $dl";
	      $conn->exec($sql); 
		  echo " DELETE EVENT";
	   }
    catch(PDOException $e)
      {
         echo $sql . "<br>" . $e->getMessage();
      }
	  $this->deleteBooking($conn,$dl);
	  $this->deleteSeats($conn,$dl);
	  $conn = null;      
    }
////////////////////////////////////
public function deleteBooking($conn, $dl)
{
   try
    {
	$sql="SELECT * FROM  booking";
	$rows=$conn->query($sql); 
	while($row=$rows->fetch(PDO::FETCH_OBJ))
	{
		$sql1="delete from booking where event_id= $dl";
		$conn->exec($sql1);
		?><h2 align="center" > <?php echo "DELETE BOOKING  "; ?> </h2><?php
	}
    } 
	catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }	
}
/////////////////////////////
public function deleteSeats($conn,$dl)
{     
   try{
        $sql="SELECT * FROM  seats";
	    $rowss=$conn->query($sql);
	         while($row1=$rowss->fetch(PDO::FETCH_OBJ))
	        {
		      $s1="delete from seats where event_id= $dl ";
		      $conn->exec($s1);
		
	        }
			  ?><h2 align="center" > <?php echo "DELETE seats"; ?> </h2><?php
	   }
	        
   catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }
}
	
	
//---------------------------------- end delete-----------------------------//
 /////////////////////////////////////////updateEvent
 public function formupdate($conn,$title,$date,$time,$location,
			 $numOfSeats,$description)
{?>
  <form action="#" method="post">
  title: <input type="text" name="newti" value=<?php echo $title ?>>
  data: <input type="date" name="data" value=<?php echo $date ?>>
  time: <input type="time" name="time" value=<?php echo $time ?> >
  location: <input type="text" name="location" value=<?php echo $location ?>>
    seats: <input type="number" name="seats" value=<?php echo $numOfSeats ?>>
  des: <input type="text" name="des" value=<?php echo $description ?>>
  <input type="submit" name="update1" value="update">
  </form>
  <?php
}
/////////////////////////////////////////////////
 
public function validationformupdate($title, $date, $time, $location, $numOfSeats, $description) {
    $errors = [];

    // التحقق من عنوان الحدث
    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    // التحقق من تاريخ الحدث
    if (empty($date)) {
        $errors[] = "Date is required.";
    } elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date)) {
        $errors[] = "Invalid date format. Use YYYY-MM-DD.";
    }

    // التحقق من وقت الحدث
    if (empty($time)) {
        $errors[] = "Time is required.";
    }

    // التحقق من الموقع
    if (empty($location)) {
        $errors[] = "Location is required.";
    }

    // التحقق من عدد المقاعد
    if (!is_numeric($numOfSeats) || $numOfSeats < 1) {
        $errors[] = "Number of seats must be a positive integer.";
    }

    // التحقق من الوصف
    if (empty($description)) {
        $errors[] = "Description is required.";
    }

    return $errors;
}
///////////////////////end validationformupdate//////////////////////////
public function callvalidationformupdate($conn, $title, $date, $time, $location, $numOfSeats, $description) 
{
    $errors = $this->validationformupdate($title, $date, $time, $location, $numOfSeats, $description);

    if (!empty($errors)) {
        foreach ($errors as $error) 
    {
            echo "<p style='color:red;'>$error</p>";
        }
     $this->formupdate($conn,$title,$date,$time,$location,$numOfSeats,$description);
    $errors = $this->validationformupdate($title, $date, $time, $location, $numOfSeats, $description);
    } 
  else 
  {
       echo"done";
    }
}
////////end callvalidationformupdate//////////
public function dataEdit($conn)
{
  $eventId=$_SESSION['id']; 
     try
    {
       $sql="SELECT title , event_date , event_time , location , seats_number , description
     FROM events where event_id='$eventId' ";
     $rows=$conn->query($sql);
     if($rows->rowCount() != 0)
     {
       $row=$rows->fetch(PDO::FETCH_OBJ);
          $title=$row->title;
            $date=$row->event_date;
            $time=$row->event_time;
            $location=$row->location;
            $numOfSeats=$row->seats_number;
            $description=$row->description;
      $this->formupdate($conn,$title,$date,$time,$location,
       $numOfSeats,$description);
     }
       else { echo " not found"; }     
    }
   catch(PDOException $e) 
   {
    echo "Error: " . $e->getMessage();
   }  
  
}
//////////////////////end dataEdit///////////////////////////
/////////////////////////////////////////////////
public function update($conn)
{
$eventId=$_SESSION['id'];
$title=$_POST['newti'];
$date=$_POST['data'];
$time=$_POST['time'];
$location=$_POST['location'];
$numOfSeats=$_POST['seats'];
$description=$_POST['des'];
 
   try
    {
  $query="UPDATE events SET title='$title', event_date='$date' , event_time='$time',
  location='$location'  , seats_number='$numOfSeats' 
  ,description='$description' where event_id='$eventId' ";
  $conn->exec($query);
  echo "Record update successfully";
    }
   catch(PDOException $e) 
   {
    echo "Error: " . $e->getMessage();
   }
  
  }
  /////////////////////////////////////////
 
//////////////////////////////////////////////// 
public function search($conn)
{
    $ti = $_POST['title'];
	$_SESSION['eventname']=$ti;
    $currentDateTime = date('Y-m-d H:i:s'); // الحصول على الوقت الحالي

    try{
        // تعديل استعلام SQL لتجنب الأحداث الماضية
        $sql = "SELECT * FROM events WHERE title = :title AND CONCAT(event_date, ' ', event_time) >= :currentDateTime";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $ti);
        $stmt->bindParam(':currentDateTime', $currentDateTime);
        $stmt->execute();
        
        if ($stmt->rowCount() != 0)
			{
            echo '<form method="post" action="#">';
            $row1 = $stmt->fetch(PDO::FETCH_OBJ) ;
                 echo '<div class="event">';
            echo '<h3>' . htmlspecialchars($row1->title) . '</h3>';
            echo 'التاريخ: ' . htmlspecialchars($row1->event_date) . '<br>';
            echo 'الوقت: ' . htmlspecialchars($row1->event_time) . '<br>';
            echo 'المكان: ' . htmlspecialchars($row1->location) . '<br>';
            echo 'عدد المقاعد: ' . htmlspecialchars($row1->seats_number) . '<br>';
            echo 'الوصف: ' . htmlspecialchars($row1->description) . '<br>';
            echo '<hr>';
                $_SESSION['event_id'] = $row1->event_id;
                echo '<a href="booking.php" class="btn btn-danger btn-large">BOOKING</a>';
                echo '</form><hr>';
				echo '</div>';
		}
           
           
		else {
            echo "NOT FOUND";
          }
		   echo  "<br>" . "<br>". "End of search result" . "<hr>" . "<br>" . "<br>";
	}
    	catch (PDOException $e)
		{
        echo "Error: " . $e->getMessage();
         }
}
}////end class
///////////////////////MAIN 
$event = new Event(1, "مهرجان الموسيقى", "2024-11-10", "19:00", "الحديقة العامة", "حدث موسيقي رائع.", "موسيقى", 100, 3);
?>
 
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Search</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .event {
            background-color: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .event h3 {
            margin: 0 0 10px;
        }

        .event hr {
            margin: 10px 0;
        }
		 
        body {
            font-family: Arial, sans-serif; /* نوع الخط */
            background-color: #f4f4f4; /* خلفية فاتحة */
            margin: 0;
            padding: 20px;
        }
        form {
            background-color: #fff; /* خلفية النموذج */
            padding: 20px;
            border-radius: 8px; /* زوايا دائرية */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* تأثير الظل */
            max-width: 600px; /* عرض محدد للنموذج */
            margin: auto; /* توسيط النموذج */
        }
        h2, h3 {
            color: #333; /* لون العناوين */
            text-align: center; /* توسيط العناوين */
        }
        input[type="text"], input[type="date"], input[type="time"], textarea {
            width: calc(100% - 20px); /* عرض إدخال النص */
            padding: 10px; /* حشوة داخلية */
            margin: 10px 0; /* هوامش أعلى وأسفل */
            border: 1px solid #ccc; /* لون الحدود */
            border-radius: 4px; /* زوايا دائرية */
            box-sizing: border-box; /* احتساب الحشوة والحدود في العرض */
        }
        textarea {
            height: 100px; /* ارتفاع المنطقة النصية */
        }
        button {
            background-color: #007BFF; /* لون الزر */
            color: white; /* لون النص */
            border: none; /* عدم وجود حدود */
            padding: 10px 15px; /* حشوة داخلية */
            border-radius: 4px; /* زوايا دائرية */
            cursor: pointer; /* تغيير المؤشر عند المرور فوق الزر */
            transition: background-color 0.3s; /* تأثير الانتقال */
        }
        button:hover {
            background-color: #0056b3; /* لون الزر عند التمرير */
        }
        #seatsInfo div {
            margin: 10px 0; /* هوامش بين معلومات المقاعد */
            padding: 10px;
            border: 1px solid #ccc; /* حدود للمعلومات */
            border-radius: 4px; /* زوايا دائرية */
            background-color: #f9f9f9; /* خلفية فاتحة */
        } 

    </style>
</head>
<body>
