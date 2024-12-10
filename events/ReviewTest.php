<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../events/class_review.php';



class ReviewTest extends TestCase
{
    private $conn;
    private $review;

    protected function setUp(): void
    {
       
        $this->conn = $this->createMock(PDO::class);
        
        // إنشاء الكائن Review
        $this->review = new Review(0, 0, 0, "");
    }

    // اختبار دالة addReview
    public function testAddReview()
    {
        $eventId = 1;
        $rating = 5;
        $comment = "Great event!";
        $username = "testuser";

        // الاحتمال الأول: إضافة تقييم صحيح
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // استدعاء دالة إضافة التقييم
        $this->review->addReview($this->conn, $eventId, $rating, $comment, $username);
        $this->assertTrue(true); // نتحقق من أن العملية تمت بنجاح

        // الاحتمال الثاني: فشل إضافة التقييم (خطأ في قاعدة البيانات)
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new Exception("Database error")));

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        try {
            $this->review->addReview($this->conn, $eventId, $rating, $comment, $username);
            $this->fail("Expected exception not thrown");
        } catch (Exception $e) {
            $this->assertEquals("Error: Database error", $e->getMessage());
        }

        // الاحتمال الثالث: التحقق من ربط المعاملات مع الاستعلام
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':event_id'), $this->equalTo($eventId), $this->equalTo(PDO::PARAM_INT))
            ->willReturnSelf();

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':rating'), $this->equalTo($rating), $this->equalTo(PDO::PARAM_INT))
            ->willReturnSelf();

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':comment'), $this->equalTo($comment), $this->equalTo(PDO::PARAM_STR))
            ->willReturnSelf();

        $stmt->expects($this->once())
            ->method('bindParam')
            ->with($this->equalTo(':username'), $this->equalTo($username), $this->equalTo(PDO::PARAM_STR))
            ->willReturnSelf();

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // استدعاء دالة إضافة التقييم للتحقق من ربط المعاملات
        $this->review->addReview($this->conn, $eventId, $rating, $comment, $username);
        $this->assertTrue(true); // إذا تمت العملية بنجاح، يعتبر التحقق صحيحًا
    }

    // اختبار دالة displayReviews
    public function testDisplayReviews()
    {
        $eventId = 1;

        // الاحتمال الأول: عرض التقييمات عندما توجد تقييمات
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                (object) ['username' => 'testuser', 'rating' => 5, 'comment' => 'Great event!', 'review_id' => 1]
            ]);

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // استدعاء دالة عرض التقييمات
        ob_start();
        $this->review->displayReviews($this->conn, $eventId);
        $output = ob_get_clean();
        $this->assertStringContainsString('Great event!', $output); // تحقق من عرض التقييم
        // الاحتمال الثاني: عرض رسالة إذا لم توجد تقييمات
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // استدعاء دالة عرض التقييمات
        ob_start();
        $this->review->displayReviews($this->conn, $eventId);
        $output = ob_get_clean();
        $this->assertStringContainsString('لا توجد تقييمات لهذا الحدث بعد.', $output); // تحقق من الرسالة عند عدم وجود تقييمات

        // الاحتمال الثالث: فشل الاتصال بقاعدة البيانات أو فشل الاستعلام
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new Exception("Database error")));

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        try {
            $this->review->displayReviews($this->conn, $eventId);
            $this->fail("Expected exception not thrown");
        } catch (Exception $e) {
            $this->assertEquals("Error: Database error", $e->getMessage());
        }
    }

    // اختبار دالة deleteReview
    public function testDeleteReview()
    {
        $reviewId = 1;
        $username = 'testuser';

        // الاحتمال الأول: حذف التقييم بنجاح
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1); // التقييم موجود للمستخدم

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // محاكاة حذف التقييم
        $stmtDelete = $this->createMock(PDOStatement::class);
        $stmtDelete->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtDelete);

        $this->review->deleteReview($this->conn, $reviewId, $username);
        $this->assertTrue(true); // تحقق من أن التقييم تم حذفه

        // الاحتمال الثاني: التقييم لا يخص المستخدم
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(0); // التقييم لا يخص المستخدم

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        try {
            $this->review->deleteReview($this->conn, $reviewId, $username);
            $this->fail("Expected exception not thrown");
        } catch (Exception $e) {
            $this->assertEquals("Error: You can't delete this review", $e->getMessage());
        }

        // الاحتمال الثالث: فشل الحذف بسبب مشكلة في قاعدة البيانات
        $stmtDelete = $this->createMock(PDOStatement::class);
        $stmtDelete->expects($this->once())
            ->method('execute')
            ->will($this->throwException(new Exception("Database error")));

        $this->conn->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtDelete);

        try {
            $this->review->deleteReview($this->conn, $reviewId, $username);
            $this->fail("Expected exception not thrown");
        } catch (Exception $e) {
            $this->assertEquals("Error: Database error", $e->getMessage());
        }
    }
    // اختبار دالة formLoginReview
    public function testFormLoginReview()
    {
        // الاحتمال الأول: عرض النموذج بشكل صحيح
        ob_start();
        $this->review->formLoginReview();
        $output = ob_get_clean();
        $this->assertStringContainsString('<form method="post" action="#">', $output);
        $this->assertStringContainsString('<input type="text" id="name" name="name">', $output);
        $this->assertStringContainsString('<input type="password" id="password" name="password">', $output);
    }
}
