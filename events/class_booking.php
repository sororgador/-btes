<?php 
include_once("connection.php");
 
 @session_start();

class Booking {
    private $bookingId;
    private $bookingDate;
    private $event;
    private $payment;

    public function __construct($bookingId, $event, $payment) {
        $this->bookingId = $bookingId;
        $this->bookingDate = new DateTime(); // تعيين تاريخ الحجز الحالي
        $this->event = $event;
        $this->payment = $payment;
    }

    public function createBooking($conn) 
	{
       $event_id = $_SESSION['event_id'];
       $customer=$_SESSION['customer_id']; 
	   $date= date('Y-m-d H:i:s');
	   try{	
	
	$sql="INSERT INTO booking(customer_id,event_id,booking_date)
	values('$customer', '$event_id','$date')";
	$conn->exec($sql);
    echo "Record insert successfully "; 
 
}
catch(PDOException $e)
    {
    echo $sql . "<br>" . $e->getMessage();
    }

$conn = null;
    }

public function showBooking($conn) 
{
    $title = $_SESSION['eventname'];
    $customerName = $_SESSION['customer'];
    $price = $_SESSION['price'];
 

    // طباعة التذكرة بتنسيق جميل
?>

    <div class="ticket-container">
        <h2>تذكرة الحجز</h2>
        <hr>
        <p><strong>عنوان الحدث:</strong> <?php echo htmlspecialchars($title); ?></p>
        <p><strong>اسم العميل:</strong> <?php echo htmlspecialchars($customerName); ?></p>
        <p><strong>  سعر المقعد:</strong> <?php echo htmlspecialchars($price); ?> dl</p>
        

        <hr>
        <div class="ticket-footer">شكرًا لحجزكم معنا!</div>
    </div>
    <?php
}

}//end class
$bookingId = 123; // رقم الحجز
$event = "حفلة موسيقية"; // اسم الحدث
$payment = 150.00; // المبلغ المدفوع

// إنشاء كائن من الكلاس Booking
$booking = new Booking($bookingId, $event, $payment);
    ?>
    <style>
        .ticket-container {
            width: 350px;
            border: 1px solid #007BFF; /* لون الحدود */
            padding: 20px;
            margin: 20px auto; /* توسيط */
            text-align: center;
            background-color: #f0f8ff; /* خلفية فاتحة */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* تأثير الظل */
            font-family: Arial, sans-serif; /* نوع الخط */
        }
        .ticket-container h2 {
            color: #007BFF; /* لون العنوان */
            margin-bottom: 10px;
        }
        .ticket-container p {
            margin: 5px 0; /* هوامش فقرة */
            font-size: 16px; /* حجم الخط */
        }
        .ticket-footer {
            margin-top: 20px;
            font-weight: bold; /* جعل النص بالخط العريض */
        }
    </style>
