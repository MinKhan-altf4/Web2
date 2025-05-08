<?php
require_once 'auth.php';
require_once 'db.php';

function checkDuplicates($conn, $username, $email, $id = null) {
    $where = $id ? "AND id != ?" : "";
    $sql = "SELECT username, email FROM user WHERE (username = ? OR email = ?) $where";
    
    $stmt = $conn->prepare($sql);
    if($id) {
        $stmt->bind_param("ssi", $username, $email, $id);
    } else {
        $stmt->bind_param("ss", $username, $email);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $duplicates = [];
    
    while($row = $result->fetch_assoc()) {
        if($row['username'] === $username) {
            $duplicates[] = 'username';
        }
        if($row['email'] === $email) {
            $duplicates[] = 'email';
        }
    }
    
    return $duplicates;
}

if(isset($_POST['save'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $status = $_POST['status'];
    
    // Validate data
    if(empty($username) || empty($fullname) || empty($phone) || 
       empty($address) || empty($city) || empty($gender) || empty($email)) {
        header("Location: user.php?error=empty_fields");
        exit();
    }
    
    // Validate phone number
    if(strlen($phone) !== 10 || !preg_match("/^[0-9]+$/", $phone)) {
        header("Location: user.php?error=invalid_phone");
        exit();
    }
    
    // Validate username length
    if(strlen($username) > 25) {
        header("Location: user.php?error=username_too_long");
        exit();
    }
    
    // Validate fullname length
    if(strlen($fullname) > 30) {
        header("Location: user.php?error=fullname_too_long");
        exit();
    }
    
    // Validate password for new users
    if(!isset($_POST['id']) && strlen($_POST['password']) < 6) {
        header("Location: user.php?error=password_too_short");
        exit();
    }

    // If updating and password is provided, validate it
    if(isset($_POST['id']) && !empty($_POST['password']) && strlen($_POST['password']) < 6) {
        header("Location: user.php?error=password_too_short");
        exit();
    }
    
    // Check for duplicates before insert/update
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $duplicates = checkDuplicates($conn, $username, $email, $id);
    
    if(!empty($duplicates)) {
        $errors = [];
        if(in_array('username', $duplicates)) {
            $errors[] = "Username already exists";
        }
        if(in_array('email', $duplicates)) {
            $errors[] = "Email already exists";
        }
        header("Location: user.php?error=" . urlencode(implode(", ", $errors)));
        exit();
    }
    
    // Nếu là thêm mới
    if(!isset($_POST['id'])) {
        $password = $_POST['password'];
        $original_password = $password; // Lưu mật khẩu gốc
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, fullname, phone, address, city, gender, email, password, original_password, role, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $username, $fullname, $phone, $address, $city, $gender, $email, $hashed_password, $original_password, $role, $status);
    }
    // Nếu là cập nhật
    else {
        $id = $_POST['id'];
        if(!empty($_POST['password'])) {
            $password = $_POST['password'];
            $original_password = $password; // Lưu mật khẩu gốc
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET username=?, fullname=?, phone=?, address=?, city=?, gender=?, email=?, password=?, original_password=?, role=?, status=? 
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssssi", $username, $fullname, $phone, $address, $city, $gender, $email, $hashed_password, $original_password, $role, $status, $id);
        } else {
            $sql = "UPDATE user SET username=?, fullname=?, phone=?, address=?, city=?, gender=?, email=?, role=?, status=? 
                    WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssi", $username, $fullname, $phone, $address, $city, $gender, $email, $role, $status, $id);
        }
    }

    if($stmt->execute()) {
        header("Location: user.php");
    } else {
        header("Location: user.php?error=1");
    }
    exit();
}
?>

<!-- Thêm vào phần head của user.php -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.error-message {
    display: none;
    color: red;
    font-size: 12px;
    margin-top: 5px;
    font-style: italic;
}

.form_group input.touched:invalid {
    border-color: red;
}

.form_group input.touched:invalid + .error-message {
    display: block;
}

.form_group input:focus {
    border-color: #4CAF50;
    outline: none;
}

.form_group input.touched:valid {
    border-color: #4CAF50;
}

/* Thêm style cho SweetAlert2 */
.swal2-popup {
    font-size: 0.9rem !important;
}
</style>