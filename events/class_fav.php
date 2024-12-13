<?php
include_once("connection.php"); // تضمين ملف الاتصال بقاعدة البيانات
include_once("class_event.php"); // تضمين ملف يحتوي على تعريفات الفئات المتعلقة بالأحداث

// واجهة المفضلة
interface FavoriteInterface {
    public function save($conn);  // دالة لحفظ المفضل في قاعدة البيانات
    public function delete($conn); // دالة لحذف المفضل من قاعدة البيانات
}

// فئة المفضل
class Favorite implements FavoriteInterface {
    private $username; // اسم المستخدم الذي يملك المفضل
    public $title;     // عنوان المفضل

    // مُنشئ الفئة
    public function __construct($username, $title) {
        $this->username = $username; // تعيين اسم المستخدم
        $this->title = $title;       // تعيين عنوان المفضل
    }

  // دالة لحفظ المفضل في قاعدة البيانات
public function save($conn) {
    try {
        $sql = "INSERT INTO favorites (username, title) VALUES (:username, :title)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->username); // ربط اسم المستخدم
        $stmt->bindParam(':title', $this->title);       // ربط عنوان المفضل
        return $stmt->execute(); // تنفيذ الاستعلام وإرجاع النتيجة
    } catch (PDOException $e) {
        // يمكنك تسجيل الخطأ أو معالجته هنا
        echo "خطأ في حفظ المفضل: " . $e->getMessage();
        return false; // إرجاع false في حالة حدوث خطأ
    }
}

// دالة لحذف المفضل من قاعدة البيانات
public function delete($conn) {
    try {
        $sql = "DELETE FROM favorites WHERE username = :username AND title = :title";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $this->username); // ربط اسم المستخدم
        $stmt->bindParam(':title', $this->title);       // ربط عنوان المفضل
        return $stmt->execute(); // تنفيذ الاستعلام وإرجاع النتيجة
    } catch (PDOException $e) {
        // يمكنك تسجيل الخطأ أو معالجته هنا
        echo "خطأ في حذف المفضل: " . $e->getMessage();
        return false; // إرجاع false في حالة حدوث خطأ
    }
}

// فئة المصنع
class FavoriteFactory {
    // دالة لإنشاء كائن مفضل جديد
    public static function createFavorite($username, $title) {
        return new Favorite($username, $title); // إرجاع كائن جديد من فئة Favorite
    }
}

// واجهة المراقب
interface Observer {
    public function update(Favorite $favorite, $action); // دالة لتحديث المراقب عند حدوث تغيير
}

// فئة المستخدم (Observer)
class User implements Observer {
    private $name; // اسم المستخدم المراقب

    // مُنشئ الفئة
    public function __construct($name) {
        $this->name = $name; // تعيين اسم المستخدم
    }

    // دالة لتحديث المراقب
    public function update(Favorite $favorite, $action) {
        echo "{$this->name} notified: Your favorite '{$favorite->title}' has been {$action}.\n"; // إخطار المستخدم
    }
}

// فئة المفضلين
class Fav {
    private $favorites = []; // مصفوفة لتخزين المفضلات
    private $observers = []; // مصفوفة لتخزين المراقبين
    
    // دالة لإضافة مراقب
    public function addObserver(Observer $observer) {
        $this->observers[] = $observer; // إضافة المراقب إلى المصفوفة
    }

    // دالة لإخطار المراقبين
    public function notify($favorite, $action) {
        foreach ($this->observers as $observer) {
            $observer->update($favorite, $action); // استدعاء دالة update لكل مراقب
        }
    }

    // دالة لعرض نموذج البحث عن المفضلات
    public function searchViewFormCheckbox($conn) {
        $name = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة

        try {
            $sql = "SELECT * FROM booking WHERE customer_name = :customer_name"; // استعلام لجلب الحجوزات
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':customer_name', $name); // ربط اسم المستخدم
            $stmt->execute();

            ?><form method="post" action="save_fav.php"><?php // نموذج حفظ المفضل
            $n = $stmt->rowCount(); // الحصول على عدد السجلات 
            if ($n > 0) { // التحقق من وجود سجلات
                while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {    
                    $id = $row->event_id; // الوصول إلى event_id من صف الحجز
                     
                    // استعلام لجلب تفاصيل الحدث
                    $sq = "SELECT * FROM events WHERE event_id = :event_id";
                    $stmt1 = $conn->prepare($sq);
                    $stmt1->bindParam(':event_id', $id);
                    $stmt1->execute();
                    
                    // الحصول على تفاصيل الحدث
                    $row1 = $stmt1->fetch(PDO::FETCH_OBJ);
                    
                    // تحقق مما إذا كان هناك حدث
                    if ($row1) {
                        ?><input type='checkbox' name='titles[]' value="<?php echo htmlspecialchars($row1->title); ?>">
                        <?php echo htmlspecialchars($row1->title) . "<br>"; // عرض عنوان الحدث
                    }
                }
                ?> <input type="submit" name="ok" value="add"><?php // زر الإرسال
            } else {
                echo "no record"; // إذا لم توجد سجلات
            }
            ?>
            </form><?php
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // عرض رسالة خطأ في حالة حدوث استثناء
        }			
    } // نهاية الدالة

    // دالة لعرض المفضلات والاختيار منهم للحذف
    public function displayFavoritesDelete($conn) {
        $username = $_SESSION['username']; // الحصول على اسم المستخدم من الجلسة

        try {
            // استعلام لجلب المفضلات للمستخدم المحدد
            $sql = "SELECT title FROM favorites WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username); // ربط اسم المستخدم
            $stmt->execute();

            // الحصول على النتائج
            $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // التحقق مما إذا كان هناك مفضلات
            if (count($favorites) > 0) {
                echo "<h2>قائمة المفضلات الخاصة بك</h2>";
                echo "<form method='post' action='#'>"; // نموذج حذف المفضلات
                echo "<table border='1'>";
                echo "<tr><th>اختر</th><th>عنوان المفضل</th></tr>";
           ?><form method="post" action="#"><?php
                // عرض العناوين في جدول
                foreach ($favorites as $favorite) {
                    echo "<tr>";
                    echo "<td><input type='checkbox' name='titles[]' value='" . htmlspecialchars($favorite['title']) . "'></td>"; // خانة اختيار
                    echo "<td>" . htmlspecialchars($favorite['title']) . "</td>"; // عرض عنوان المفضل
                    echo "</tr>";
                }

                echo "</table>";
                echo "<input type='submit' name='delete' value='حذف المفضلات المحددة'>"; // زر الحذف
                echo "</form>";
            } else {
                echo "لا توجد مفضلات."; // إذا لم توجد مفضلات
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage(); // عرض رسالة خطأ في حالة حدوث استثناء
        }
    }

    // دالة لحفظ المفضلات
    public function saveFav($conn) {
        if (isset($_POST['titles']) && is_array($_POST['titles'])) { // التحقق مما إذا كانت العناوين موجودة
            $titles = $_POST['titles']; // الحصول على العناوين
            $username = $_SESSION['username']; // الحصول على اسم المستخدم

            foreach ($titles as $title) {
                $favorite = FavoriteFactory::createFavorite($username, $title); // إنشاء مفضل جديد
                
                // تحقق مما إذا كان العنوان موجودًا بالفعل
                $checkSql = "SELECT COUNT(*) FROM favorites WHERE username = :username AND title = :title";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bindParam(':username', $username); // ربط اسم المستخدم
                $checkStmt->bindParam(':title', $title); // ربط عنوان المفضل
                $checkStmt->execute();
                $exists = $checkStmt->fetchColumn(); // الحصول على عدد السجلات

                if ($exists == 0) { // إذا لم يكن موجودًا
                    if ($favorite->save($conn)) { // حفظ المفضل
                        $this->notify($favorite, 'added'); // إخطار المراقبين
                    }
                } else {
                    echo "العنوان '$title' موجود بالفعل في المفضلات.<br>"; // إذا كان موجودًا
                }
            }
            echo "تمت إضافة المفضلات بنجاح!"; // رسالة نجاح
        } else {
            echo "لم يتم اختيار أي عنوان."; // إذا لم يتم اختيار أي عنوان
        }
    }

    // دالة لحذف المفضلات
    public function deleteFavorites($conn) {
        $username = $_SESSION['username']; // الحصول على اسم المستخدم

        if (isset($_POST['titles']) && is_array($_POST['titles'])) { // التحقق مما إذا كانت العناوين موجودة
            $titles = $_POST['titles']; // الحصول على العناوين

            foreach ($titles as $title) {
                $favorite = FavoriteFactory::createFavorite($username, $title); // إنشاء مفضل جديد
                
                if ($favorite->delete($conn)) { // حذف المفضل
                    $this->notify($favorite, 'deleted'); // إخطار المراقبين
                }
            }
            echo "تم حذف المفضلات المحددة بنجاح!"; // رسالة نجاح
        } else {
            echo "لم يتم اختيار أي عنوان للحذف."; // إذا لم يتم اختيار أي عنوان
        }
    }

    // دالة لعرض المفضلات
public function displayFavorites($conn) {
    $username = $_SESSION['username']; // الحصول على اسم المستخدم

    // استعلام لجلب كافة بيانات الأحداث المرتبطة بالمفضلات للمستخدم
    $sql = "SELECT events.title, events.event_date, events.event_time, events.location, events.seats_number, events.description 
            FROM favorites 
            JOIN events ON favorites.title = events.title 
            WHERE favorites.username = :username"; 
            
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username); // ربط اسم المستخدم
    $stmt->execute();
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC); // الحصول على النتائج

    if (count($favorites) > 0) { // التحقق من وجود مفضلات
        echo "<h2>قائمة المفضلات الخاصة بك</h2>";
        echo "<table border='1'>";
        echo "<tr>
                <th>عنوان الحدث</th>
                <th>تاريخ الحدث</th>
                <th>وقت الحدث</th>
                <th>الموقع</th>
                <th>عدد المقاعد</th>
                <th>الوصف</th>
              </tr>";

        foreach ($favorites as $favorite) {
            echo "<tr>
                    <td>" . htmlspecialchars($favorite['title']) . "</td>
                    <td>" . htmlspecialchars($favorite['event_date']) . "</td>
                    <td>" . htmlspecialchars($favorite['event_time']) . "</td>
                    <td>" . htmlspecialchars($favorite['location']) . "</td>
                    <td>" . htmlspecialchars($favorite['seats_number']) . "</td>
                    <td>" . htmlspecialchars($favorite['description']) . "</td>
                  </tr>"; // عرض تفاصيل الحدث
        }

        echo "</table>";
    } else {
        echo "لا توجد مفضلات."; // إذا لم توجد مفضلات
    }
}
}

// استخدام الكود
$fav = new Fav(); // إنشاء كائن من فئة Fav
$fav->addObserver(new User("soror")); // إضافة مستخدم كمراقب
$fav->addObserver(new User("alaa")); // إضافة مستخدم كمراقب آخر
?>
