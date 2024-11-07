<?php
include_once("class_event.php");
  session_start();
class Seats 
   {
    private $seatId;      // رقم فريد وتسلسلي يحدد رقم المقعد لكل صف
    private $priceSeat;   //  سعر المقعد
    private $status;      // حالة المقعد (مثل "محجوز"، "متوفر")
	private $eventId;

    public function __construct($seatId , $priceSeat, $status ,$eventId)
	{
        $this->seatId = $seatId;
        $this->priceSeat = $priceSeat;
        $this->status = $status;
		$this->eventId = $eventId;
	
    }
/////////////////////////////////////////////////////////
 

    public function getPriceSeat() {
        return $this->priceSeat;
     }
///////////////////////////////// دالة تقوم بتعبئة بيانات المقاعد الغير محجوزة وحسب الحدث المختار 
 public function fillIn($conn)
{
    $eventId = $_SESSION['event_id'];
    ?> <h3>المقاعد المتوفرة</h3><?php

    $seats = $this->getAvailableSeats($conn, $eventId);
    
    if ($seats) {
        $this->renderSeatsForm($seats);
    } else {
        echo "NO SEATS AVAILABLE"; // تحسين الرسالة
    }
}
///////////////////////////////
private function getAvailableSeats($conn, $eventId)
{
    try {
        $sql = "SELECT * FROM seats WHERE status = 'no' AND event_id = :eventId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':eventId', $eventId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ); // إرجاع جميع المقاعد المتاحة
    } 
	catch (PDOException $e) 
	{
        echo "خطأ: " . $e->getMessage();
        return false; // في حالة حدوث خطأ، إرجاع false
    }
}

private function renderSeatsForm($seats)
{
   

    // معالجة البيانات المرسلة عند تقديم النموذج
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking'])) {
        if (isset($_POST['selected_seats']) && is_array($_POST['selected_seats'])) {
            // تخزين عدد الكراسي المحددة في الجلسة
            $_SESSION['selected_seat_count'] = count($_POST['selected_seats']);
            $_SESSION['selected_seats'] = $_POST['selected_seats']; // تخزين تفاصيل الكراسي المحددة
            echo "عدد الكراسي المحددة: " . htmlspecialchars($_SESSION['selected_seat_count']);
        } else {
            echo "لم يتم اختيار أي كراسي.";
        }
    }

    ?>
    <form method="post" action="#">
        <div id="seatsContainer">
        <?php
        foreach ($seats as $row) {   
            ?>
            <label>
                <input type="checkbox" class="checkbox" name="selected_seats[]" value="<?php echo htmlspecialchars($row->seat_id); ?>" onchange="updateCount()">
                <?php echo "number: " . htmlspecialchars($row->seat_id) . " price: " . htmlspecialchars($row->price) . " status: " . htmlspecialchars($row->status) . "<br>"; ?>
            </label><br>
            <?php 
        }
        ?>
        </div>
        <div class="result" id="result">عدد الاختيارات: 0</div>
        <button type="submit" name="book" class="btn btn-danger btn-large" onclick="return validateSelection();">تأكيد الحجز</button>
    </form> 
    <hr>
    <script>
    function updateCount() {
        const checkboxes = document.querySelectorAll('.checkbox');
        let count = 0;

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                count++;
            }
        });

        document.getElementById('result').textContent = 'عدد الاختيارات: ' + count;
    }

    function validateSelection() {
        const checkboxes = document.querySelectorAll('.checkbox');
        let selected = false;

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                selected = true;
            }
        });

        if (!selected) {
            alert("يرجى اختيار مقعد واحد على الأقل.");
            return false; // منع تقديم النموذج
        }

        return true; // السماح بتقديم النموذج
    }
    </script>
    <?php
}
}// مثال على كيفية استخدام الكلاس
$seat = new Seats(1, "عادي", 100.0, 10, "متوفر");


?>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حساب الاختيارات</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .result {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>