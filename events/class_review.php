<?php
include_once("\get\connection.php");
include_once("event.php");

class Review {

    private $reviewId;
    private $eventId;
    private $rating;
	private $comment;
    

    public function __construct($reviewId, $eventId, $rating, $comment)
    {
        $this->reviewId = $reviewId;
        $this->eventId = $eventId;
        $this->rating = $rating;
		$this->comment = $comment;
       
    }

    


public function addReview($conn, $eventId, $rating, $comment, $username)//دالة تقوم بإضافة التعليقات والتقييمات في قاعدة البيانات
{
    try {
        $sql = "INSERT INTO review (event_id, rating, comment, username) 
                VALUES (:event_id, :rating, :comment, :username)";
        $stmt = $conn->prepare($sql);
        
        // ربط المعاملات مع المتغيرات
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        
        $stmt->execute();
        
        echo "تم إضافة تقييمك وتعليقك بنجاح.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}


public function formLoginReview()//دالة بها نموذج لتسجل دخول المستخدم عند اضافة تقييم
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
public function displayReviews($conn, $eventId)//دالة لعرض التقييمات الخاصة بكل حدث
 {
    try {
		//احضار بيانات القييم من قاعدة البيانات
        $sql = "SELECT rating, comment, username, review_id FROM review WHERE event_id = :event_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);
        if (count($reviews) > 0)//التأكد من وجود تقييمات لهذا الحدث 
		{
            foreach ($reviews as $row) {
                echo "<div class='review'>";
                echo "<p>اسم المستخدم: " . htmlspecialchars($row->username) . "</p>";//عرض اسم المستخدم الذي قام بالتقييم
                echo "<p>تقييم: " . $row->rating . " نجوم</p>";
                echo "<p>تعليق: " . htmlspecialchars($row->comment) . "</p>";  // عرض التعليق

                // إضافة زر الحذف للتعليق الذي ينتمي إلى المستخدم
                if (isset($_SESSION['username']) && $_SESSION['username'] == $row->username) {
                    echo '<form method="POST" action="#">
                            <input type="hidden" name="review_id" value="' . $row->review_id . '">
                            <input type="submit" value="حذف التقييم" name="delete_review">
                          </form>';
                }
                echo "</div>";
            }
        }//try
		else {
            echo "<p>لا توجد تقييمات لهذا الحدث بعد.</p>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
///////////////////////////////////////////
public function deleteReview($conn, $reviewId, $username)//دالة تقوم بحذف تعليقات المستخدم
 {
    try {
        // التحقق من أن التقييم ينتمي للمستخدم الحالي باستخدام review_id و username
        $sql = "SELECT * FROM review WHERE review_id = :review_id AND username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':review_id', $reviewId, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // إذا كان التقييم للمستخدم الحالي، نقوم بحذفه
            $sqlDelete = "DELETE FROM review WHERE review_id = :review_id";
            $stmtDelete = $conn->prepare($sqlDelete);
            $stmtDelete->bindParam(':review_id', $reviewId, PDO::PARAM_INT);
            $stmtDelete->execute();
            
            echo "تم حذف التقييم بنجاح.";
        } else {
            echo "لا يمكنك حذف هذا التقييم لأنه لا يخصك.";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
    }
	/////////////////////
	$review = new Review(0, 0, 0,"");
