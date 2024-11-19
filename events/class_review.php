<?php
include_once("connection.php");
include_once("event.php");

class Review {

    private $reviewId;
    private $eventId;
    private $rating;
    

    public function __construct($reviewId, $eventId, $rating )
    {
        $this->reviewId = $reviewId;
        $this->eventId = $eventId;
        $this->rating = $rating;
       
    }

    

public function addReview($conn, $eventId, $rating, $username)
{

try{
	$sql= "INSERT INTO review (event_id , rating , username) VALUES ($eventId , $rating , '$username')";
	$conn->exec($sql);
	echo "اضافة تقييمك بنجاح";
}
	
     catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
public function displayReviews($conn, $eventId) {
    try {
        $sql = "SELECT rating, username FROM review WHERE event_id = :event_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);

        if (count($reviews) > 0) {
            foreach ($reviews as $row) {
                echo "<div class='review'>";
                echo "<p>اسم المستخدم: " . htmlspecialchars($row->username) . "</p>";
                echo "<p>تقييم: " . $row->rating . " نجوم</p>";
                echo "</div>";
            }
        } else {
            echo "<p>لا توجد تقييمات لهذا الحدث بعد.</p>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
    }
	/////////////////////
	$review = new Review(0, 0, 0);