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

// الحصول على البيانات من النموذج
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور

// التحقق من وجود البريد الإلكتروني
$sql = "SELECT * FROM if0_36969144_users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "البريد الإلكتروني مستخدم بالفعل.";
} else {
    // إنشاء رمز تحقق
    $verification_code = bin2hex(random_bytes(16)); // توليد رمز عشوائي
    // إدخال المستخدم الجديد في قاعدة البيانات
    $sql = "INSERT INTO if0_36969144_users (name, email, password, verification_code) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $verification_code);
    
    if ($stmt->execute()) {
        // إرسال البريد الإلكتروني للتحقق
        $to = $email;
        $subject = "تحقق من بريدك الإلكتروني";
        $message = "مرحبا $name,\n\nلتحقق من بريدك الإلكتروني، يرجى النقر على الرابط التالي:\n";
        $message .= "https://shihabhataf.rf.gd/verify.php?code=$verification_code\n\n";
        $message .= "شكرًا لك!";
        $headers = "From: no-reply@yourdomain.com";

        mail($to, $subject, $message, $headers);

        echo "تم التسجيل بنجاح! تحقق من بريدك الإلكتروني.";
    } else {
        echo "حدث خطأ: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>