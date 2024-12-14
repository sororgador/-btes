<?php
include_once("connection.php"); 

// فئة الحجز باستخدام Singleton
class Booking {
    private $bookingId; // معرف الحجز
    private $bookingDate; // تاريخ الحجز
    private $event; // الحدث المرتبط بالحجز
    private $payment; // حالة الدفع
    private static $instance = null; // كائن واحد فقط من فئة Booking (نمط Singleton)

    // دالة البناء الخاصة (private) لتجنب إنشاء كائنات متعددة من نفس الفئة
    private function __construct($bookingId, $event, $payment) {
        $this->bookingId = $bookingId;
        $this->bookingDate = new DateTime(); // تعيين تاريخ الحجز الحالي
        $this->event = $event;
        $this->payment = $payment;
    }

    // دالة لإرجاع الكائن الوحيد من Booking (نمط Singleton)
    public static function getInstance($bookingId = null, $event = null, $payment = null) {
        if (self::$instance === null) {
            self::$instance = new Booking($bookingId, $event, $payment); // إذا لم يتم إنشاء الكائن بعد، سيتم إنشاؤه
        }
        return self::$instance; // إرجاع الكائن الوحيد
    }

    // دالة لإنشاء الحجز
    public function createBooking($conn) {
        $event_id = $_SESSION['event_id']; // جلب معرف الحدث من الجلسة
        $customer = $_SESSION['customer_id']; // جلب معرف العميل من الجلسة
        $date = date('Y-m-d H:i:s'); // تعيين تاريخ ووقت الحجز الحالي
        
        try {
            // استعلام لإضافة الحجز إلى قاعدة البيانات
            $sql = "INSERT INTO booking(customer_id, event_id, booking_date) VALUES ('$customer', '$event_id', '$date')";
            $conn->exec($sql); // تنفيذ الاستعلام
            $this->notifyObservers("تم إنشاء الحجز بنجاح."); // إخطار المراقبين بتحديث الحجز
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage(); // التعامل مع الخطأ إذا حدث
        }
    }
    
    // دالة لعرض الحجوزات
    public function viewBooking($conn) {
        $name = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة
        
        try {
            // استعلام لجلب الحجوزات الخاصة بالمستخدم
            $sql = "SELECT * FROM booking WHERE customer_name = :customer_name"; 
            $stmt = $conn->prepare($sql); // تحضير الاستعلام
            $stmt->bindParam(':customer_name', $name); // ربط المتغيرات في الاستعلام
            $stmt->execute(); // تنفيذ الاستعلام
            $n = $stmt->rowCount(); // الحصول على عدد السجلات 

            if ($n > 0) {
                // إذا كانت هناك حجوزات، سيتم عرض التفاصيل
                while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
                    $id = $row->event_id; // جلب معرف الحدث المرتبط بالحجز
                    $_SESSION['customer_name'] = $row->customer_name; // تخزين اسم العميل في الجلسة
                    $sq = "SELECT * FROM events WHERE event_id = :event_id"; // استعلام للحصول على تفاصيل الحدث
                    $stmt1 = $conn->prepare($sq);
                    $stmt1->bindParam(':event_id', $id);
                    $stmt1->execute();
                    $row1 = $stmt1->fetch(PDO::FETCH_OBJ);
                    
                    // عرض تفاصيل الحجز
                    echo "<div class='booking'>";
                    echo "<p><strong>عنوان الحدث:</strong> " . htmlspecialchars($row1->title) . "</p>";
                    echo "<p><strong>التاريخ:</strong> " . htmlspecialchars($row1->event_date) . "</p>";
                    echo "<p><strong>الوقت:</strong> " . htmlspecialchars($row1->event_time) . "</p>";
                    echo "<p><strong>الوصف:</strong> " . htmlspecialchars($row1->description) . "</p>";

                    // إضافة نموذج لإلغاء الحجز
                    if (isset($_SESSION['username']) && $_SESSION['username'] == $row->customer_name) {
                        echo '<form method="POST" action="">
                                <input type="hidden" name="event_id" value="' . $row1->event_id . '">
                                <input type="submit" value="الغاء حجز" name="delete_booking">
                              </form>';
                    }
                    echo "</div>";
                }
            }
            else{
                echo "ليس لديك حجوزات لتقوم بإلغاءها";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // التعامل مع الخطأ في حالة حدوثه
        }
    }

    // دالة للتحقق من الوقت بين الوقت الحالي ووقت الحدث
    public function checkEventTime($event_id, $conn) {
        try {
            // استعلام للحصول على تفاصيل الحدث (التاريخ والوقت)
            $sql_event = "SELECT event_date, event_time FROM events WHERE event_id = :event_id";
            $stmt_event = $conn->prepare($sql_event);
            $stmt_event->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt_event->execute();
            $event = $stmt_event->fetch(PDO::FETCH_ASSOC);

            // دمج التاريخ والوقت للحصول على الوقت الكامل للحدث
            $event_datetime = $event['event_date'] . ' ' . $event['event_time'];
            $event_datetime = new DateTime($event_datetime);
            $current_datetime = new DateTime(); // الوقت الحالي

            // حساب الفرق بين الوقت الحالي ووقت الحدث
            $interval = $event_datetime->diff($current_datetime);

            // إذا كان الفرق أقل من 24 ساعة، لا يسمح بالإلغاء
            if ($interval->h < 24 && $interval->d == 0) {
                return false; // لا يسمح بالإلغاء
            } else {
                return true; // يسمح بالإلغاء
            }
        } catch (PDOException $e) {
            echo "حدث خطأ أثناء محاولة التحقق من الوقت: " . $e->getMessage(); // التعامل مع الخطأ
            return false;
        }
    }

    // دالة لإلغاء الحجز
    public function cancelBooking($conn) {
        if (isset($_POST['delete_booking']) && isset($_POST['event_id'])) {
            $event_id = $_POST['event_id']; // جلب معرف الحدث لإلغاء الحجز
            $customer = $_SESSION['username']; // الحصول على اسم العميل من الجلسة

            try {
                // التحقق من وجود الحجز في قاعدة البيانات
                $sql = "SELECT * FROM booking WHERE event_id = :event_id AND customer_name = :customer_name";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                $stmt->bindParam(':customer_name', $customer, PDO::PARAM_STR); // استخدام PARAM_STR لأن customer_name نص
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    // إذا كان الحجز موجوداً
                    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
                    $event_id = $booking['event_id'];

                    // التحقق من الوقت إذا كان الفرق أقل من 24 ساعة
                    if (!$this->checkEventTime($event_id, $conn)) {
                        echo "لا يمكن إلغاء الحجز لأن الحدث سيحدث خلال أقل من 24 ساعة."; // عرض رسالة للمستخدم
                    } else {
                        // حذف الحجز من قاعدة البيانات
                        $delete_sql = "DELETE FROM booking WHERE event_id = :event_id AND customer_name = :customer_name";
                        $delete_stmt = $conn->prepare($delete_sql);
                        $delete_stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
                        $delete_stmt->bindParam(':customer_name', $customer, PDO::PARAM_STR);
                        $delete_stmt->execute(); // تنفيذ الاستعلام للحذف
                        echo "تم إلغاء الحجز بنجاح."; // إظهار رسالة تأكيد
                    }
                } else {
                    echo "لم يتم العثور على حجز لهذا الحدث."; // إذا لم يتم العثور على الحجز
                }
            } catch (PDOException $e) {
                echo "حدث خطأ أثناء محاولة إلغاء الحجز: " . $e->getMessage(); // التعامل مع الخطأ
            }
        } else {
            echo "لم يتم تحديد الحدث لإلغاء الحجز."; // إذا لم يتم تحديد الحدث
        }
    }
}

$booking = Booking::getInstance(0, "", 0); // الحصول على الكائن الوحيد من Booking

?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    .booking {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin: 20px;
        color: #333;
    }

    .booking p {
        font-size: 16px;
        line-height: 1.6;
    }

    .booking strong {
        color: #333;
    }

    form {
        margin-top: 10px;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }
</style>


