<?php
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thông tin admin với plain password để đăng nhập
$admin_accounts = [
    [
        'username' => 'admin1',
        'email' => 'admin1@gmail.com', 
        'password' => '123456',
        'role' => 'admin',
        'status' => 1,
        'fullname' => 'Admin System 1',
        'phone' => '0123456789',
        'address' => 'Ha Noi',
        'gender' => 'Male'
    ],
    [
        'username' => 'admin2',
        'email' => 'admin2@gmail.com',
        'password' => '123456', 
        'role' => 'admin',
        'status' => 1,
        'fullname' => 'Admin System 2',
        'phone' => '0123456788',
        'address' => 'Ha Noi',
        'gender' => 'Male'
    ],
    [
        'username' => 'admin3',
        'email' => 'admin3@gmail.com',
        'password' => '123456',
        'role' => 'admin', 
        'status' => 1,
        'fullname' => 'Admin System 3',
        'phone' => '0123456787',
        'address' => 'Ha Noi',
        'gender' => 'Male'
    ]
];

foreach($admin_accounts as $admin) {
    try {
        // Mã hóa mật khẩu trước khi lưu vào DB
        $hashed_password = password_hash($admin['password'], PASSWORD_DEFAULT);
        
        $check_sql = "SELECT * FROM user WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        
        if (!$check_stmt) {
            throw new Exception("Lỗi prepare check statement: " . $conn->error);
        }
        
        $check_stmt->bind_param("s", $admin['email']);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if($result->num_rows > 0) {
            $update_sql = "UPDATE user SET username=?, password=?, role=?, status=?, fullname=?, phone=?, address=?, gender=? WHERE email=?";
            $stmt = $conn->prepare($update_sql);
            
            if (!$stmt) {
                throw new Exception("Lỗi prepare update statement: " . $conn->error);
            }
            
            $stmt->bind_param("sssssssss", 
                $admin['username'],
                $hashed_password, // Lưu mật khẩu đã mã hóa
                $admin['role'],
                $admin['status'],
                $admin['fullname'],
                $admin['phone'],
                $admin['address'],
                $admin['gender'],
                $admin['email']
            );
        } else {
            $insert_sql = "INSERT INTO user (username, email, password, role, status, fullname, phone, address, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            
            if (!$stmt) {
                throw new Exception("Lỗi prepare insert statement: " . $conn->error);
            }
            
            $stmt->bind_param("ssssissss", 
                $admin['username'],
                $admin['email'],
                $hashed_password, // Lưu mật khẩu đã mã hóa
                $admin['role'],
                $admin['status'],
                $admin['fullname'],
                $admin['phone'],
                $admin['address'],
                $admin['gender']
            );
        }
        
        if($stmt->execute()) {
            echo "Thành công với tài khoản: " . $admin['email'] . "<br>";
        } else {
            echo "Lỗi SQL: " . $stmt->error . "<br>";
        }

    } catch (Exception $e) {
        echo "Lỗi: " . $e->getMessage() . "<br>";
    }
}

// Hiển thị thông tin đăng nhập với mật khẩu gốc
echo "<br>Thông tin đăng nhập (mật khẩu chưa mã hóa để đăng nhập):<br>";
echo "----------------------------------------<br>";
foreach($admin_accounts as $admin) {
    echo "Username: " . $admin['username'] . "<br>";
    echo "Email: " . $admin['email'] . "<br>";
    echo "Password để đăng nhập: " . $admin['password'] . "<br>";
    echo "----------------------------------------<br>";
}

// Hiển thị mật khẩu đã mã hóa trong database
echo "<br>Mật khẩu đã mã hóa trong database:<br>";
$sql = "SELECT email, password FROM user WHERE role='admin'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    echo "Email: " . $row['email'] . "<br>";
    echo "Hashed password: " . $row['password'] . "<br>";
    echo "----------------------------------------<br>";
}

$conn->close();
?>