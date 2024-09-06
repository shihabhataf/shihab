<?php
$servername = "sql204.infinityfree.com";
$username = "if0_36969144";
$password = "2hRz89ldqZESk";
$dbname = "if0_36969144";

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

// الحصول على رمز التحقق من الرابط
$verification_code = $_GET['code'];

// التحقق من الرمز في قاعدة البيانات
$sql = "SELECT * FROM if0_36969144_users WHERE verification_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $verification_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // تحديث حالة التحقق
    $sql = "UPDATE if0_36969144_users SET is_verified = 1, verification_code = NULL WHERE verification_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $verification_code);

    if ($stmt->execute()) {
        echo "تم التحقق من بريدك الإلكتروني بنجاح!";
    } else {
        echo "حدث خطأ أثناء التحقق.";
    }
} else {
    echo "الرمز غير صالح.";
}

$stmt->close();
$conn->close();
?>