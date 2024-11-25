<?php

use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        // إعداد اتصال قاعدة البيانات الحقيقي
        $this->conn = new PDO('mysql:host=localhost;dbname=your_database_name', 'your_username', 'your_password');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // إعداد قاعدة البيانات للاختبارات
        $this->conn->exec("CREATE TABLE IF NOT EXISTS customers (
            customer_id INT AUTO_INCREMENT PRIMARY KEY,
            customer_name VARCHAR(255),
            age INT,
            qualification VARCHAR(255),
            phone_num VARCHAR(255),
            email VARCHAR(255)
        )");

        // إدخال بيانات اختبار
        $this->conn->exec("INSERT INTO customers (customer_name, age, qualification, phone_num, email) VALUES 
            ('John Doe', 30, 'teacher', '123456789', 'john@example.com'),
            ('Jane Smith', 25, 'student', '987654321', 'jane@example.com')");
    }

    public function testDisplayCustomersAll()
    {
        ob_start();
        $this->displayCustomersAll($this->conn);
        $output = ob_get_clean();

        $this->assertStringContainsString('اختيار عميل لتعديل بياناته', $output);
        $this->assertStringContainsString('طباعة قائمة باسماء العملاء', $output);
    }

    public function testFillInCustomersAll()
    {
        ob_start();
        $this->fillInCustomersAll($this->conn);
        $output = ob_get_clean();

        $this->assertStringContainsString('John Doe', $output);
        $this->assertStringContainsString('Jane Smith', $output);
    }

    public function testDisplayCustomersQual()
    {
        $_SESSION['qual'] = 'teacher'; // تعيين المؤهل
        ob_start();
        $this->displayCustomersQual($this->conn);
        $output = ob_get_clean();

        $this->assertStringContainsString('اختيار عميل لتعديل بياناته', $output);
    }

    public function testFillInCustomersQual()
    {
        $_SESSION['qual'] = 'teacher'; // تعيين المؤهل
        ob_start();
        $this->fillInCustomersQual($this->conn);
        $output = ob_get_clean();

        $this->assertStringContainsString('John Doe', $output);
        $this->assertStringNotContainsString('Jane Smith', $output);
    }

    public function testUpdateCustomers()
    {
        $_SESSION['id'] = 1; // تعيين معرف العميل
        $_POST['name'] = 'John Updated';
        $_POST['age'] = 31;
        $_POST['qualification'] = 'teacher';
        $_POST['email'] = 'john.updated@example.com';
        $_POST['number'] = '123456789';

        // تنفيذ عملية التحديث
        $this->updatecustomers($this->conn);

        // التأكد من أن البيانات تم تحديثها
        $stmt = $this->conn->query("SELECT * FROM customers WHERE customer_id = 1");
        $customer = $stmt->fetch(PDO::FETCH_OBJ);

        $this->assertEquals('John Updated', $customer->customer_name);
        $this->assertEquals(31, $customer->age);
    }

    public function testDeleteCustomers()
    {
        $_SESSION['id'] = 1; // تعيين معرف العميل
        $this->deleteCustomers($this->conn);

        // التأكد من أن العميل تم حذفه
        $stmt = $this->conn->query("SELECT * FROM customers WHERE customer_id = 1");
        $this->assertFalse($stmt->fetch());
    }

    protected function tearDown(): void
    {
        // حذف قاعدة البيانات بعد انتهاء الاختبارات
        $this->conn->exec("DROP TABLE IF EXISTS customers");
        $this->conn = null; // إنهاء الاتصال
    }

    // تحتاج إلى تعريف الدوال هنا مباشرة، مثل:
    public function displayCustomersAll($conn)
    {
        echo '<form method="post" action="#">';
        echo '<select name="customer">';
        echo '<option></option>';
        $this->fillInCustomersAll($conn);
        echo '</select>';
        echo '<input type="submit" name="all" value="اختيار عميل لتعديل بياناته">';
        echo '<button type="submit" name="report" class="btn btn-danger btn-large">طباعة قائمة باسماء العملاء</button>';
        echo '</form>';
    }

    public function fillInCustomersAll($conn)
    {
        try {
            $sql = "SELECT * FROM customers";
            $rows = $conn->query($sql);
            
            while ($row = $rows->fetch(PDO::FETCH_OBJ)) {
                echo '<option value="' . $row->customer_id . '">' . htmlspecialchars($row->customer_name) . ' ' . htmlspecialchars($row->qualification) . ' ' . htmlspecialchars($row->age) . '</option>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function updatecustomers($conn)
    {
        $customerId = $_SESSION['id'];
        $customerName = $_POST['name'];
        $customerAge = $_POST['age'];
        $customerQualification = $_POST['qualification'];
        $customerEmail = $_POST['email'];
        $customerPhone = $_POST['number'];

        try {
            $query = "UPDATE customers SET customer_name='$customerName', age='$customerAge', qualification='$customerQualification', phone_num='$customerPhone', email='$customerEmail' WHERE customer_id='$customerId'";
            $conn->exec($query);
            echo "Record updated successfully";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function deleteCustomers($conn)
    {
        $customerId = $_SESSION['id'];
        
        try {
            $sql = "DELETE FROM customers WHERE customer_id='$customerId'";
            $conn->exec($sql);
            echo "Delete is done";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}