<?php
include_once("connection.php");
include_once("event.php");

class Review //فئة التقييم 
{

    private $reviewId; //رقم التقييم
    private $eventId; //رقم الحدث المراد تقييمه
    private $rating; //التقييم الخاص بالحدث
    private $username; //اسم المستخدم الذي يقوم بالتقييم
    

    public function __construct($reviewId, $eventId, $rating )//دالة بناء
    {
        $this->reviewId = $reviewId;
        $this->eventId = $eventId;
        $this->rating = $rating;
       
    }

    

public function addReview($conn, $eventId, $rating, $username)//دالة لإضافة تقييم
{

try{
	$sql= "INSERT INTO review (event_id , rating , username) VALUES ($eventId , $rating , '$username')"; //لاضافة لحدث واسم الذي قام بالقييم زرقم الحدث في قاعدة البيانات
	$conn->exec($sql);
	echo "اضافة تقييمك بنجاح";
   }//end try
	
     catch (Exception $e)
    {
        echo "Error: " . $e->getMessage();
    }//end catch
}//end function addReview /////////////
public function displayReviews($conn, $eventId) //دالة لعرض تقيمات حدث معين عند اختياره
	{
    try {
        $sql = "SELECT rating, username FROM review WHERE event_id = :event_id"; //لإحضار بيانات التقييم من جدول التقييم لحدث تم اخياره
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ); //جلب التقييم

        if (count($reviews) > 0)//التأكد انه يوجد صف في قاعدة البيانات به المعلومات السابقة
	{
            foreach ($reviews as $row)
	   {
                echo "<div class='review'>";
                echo "<p>اسم المستخدم: " . htmlspecialchars($row->username) . "</p>";
                echo "<p>تقييم: " . $row->rating . " نجوم</p>";
                echo "</div>";
            }
        }
	else 
	{
            echo "<p>لا توجد تقييمات لهذا الحدث بعد.</p>";
        }
    }//end try
    catch (Exception $e) 
    {
        echo "Error: " . $e->getMessage();
    }//end catch
}/////end function displayReviews ////////
    }///end class ////////
	/////////////////////
	$review = new Review(0, 0, 0); //تكوين كائن من النوع تقييم
