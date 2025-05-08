<?php
require_once 'auth.php';

// Thay thế đoạn code fetch users cũ bằng:
$sql = "SELECT * FROM user WHERE role != 'admin'";
$params = array();
$types = "";

if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%{$_GET['search']}%";
    $sql .= " AND (username LIKE ? OR email LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    $types .= "ss";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if(!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get user by ID for editing 
if(isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_sql = "SELECT * FROM user WHERE id = ? AND role != 'admin'";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_user = $stmt->get_result()->fetch_assoc();
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/accounts.css">
    <link rel="stylesheet" href="../css/grid.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* Thay đổi từ 15% xuống 5% */
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-height: 90vh;
            overflow-y: auto;
            animation: modalFadeIn 0.3s ease-out;
        }

        /* Thêm animation cho modal */
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Cập nhật style cho userInfo */
        #userInfo {
            margin-top: 20px;
            text-align: left;
            padding: 15px;
            background-color: #fff;
        }

        #userInfo p {
            margin: 10px 0;
            padding: 12px;
            background-color: #f9f9f9;
            border-radius: 4px;
            display: flex;
            flex-wrap: wrap; /* Cho phép nội dung xuống hàng */
            align-items: flex-start; /* Căn đầu dòng */
            min-height: 40px; /* Đảm bảo chiều cao tối thiểu */
            word-break: break-word; /* Cho phép từ dài tự động xuống hàng */
        }

        #userInfo strong {
            display: inline-block;
            width: 120px; /* Giảm độ rộng của label */
            color: #333;
            font-weight: 600;
            flex-shrink: 0; /* Không co label lại */
        }

        #userInfo p span {
            flex: 1; /* Cho phép nội dung mở rộng */
            padding-left: 10px; /* Tạo khoảng cách với label */
            max-width: calc(100% - 130px); /* Đảm bảo không bị tràn container */
        }

        .modal-content {
            width: 80%; /* Tăng độ rộng của modal */
            max-width: 800px; /* Giới hạn độ rộng tối đa */
            margin: 2% auto;
            max-height: 95vh; /* Tăng chiều cao tối đa */
        }

        .modal h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 1;
        }

        /* Cập nhật style cho nút đóng */
        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            z-index: 2;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Thêm media query cho mobile */
        @media screen and (max-width: 768px) {
            .modal-content {
                width: 90%;
                margin: 10% auto;
                padding: 15px;
            }

            #userInfo p {
                padding: 8px;
                font-size: 14px;
                flex-direction: column;
                align-items: flex-start;
                background-color: #f9f9f9;
                margin: 5px 0;
                border-radius: 6px;
            }

            #userInfo strong {
                width: 100%;
                margin-bottom: 4px;
                color: #666;
                font-size: 12px;
                text-transform: uppercase;
            }

            .modal h2 {
                font-size: 18px;
                padding-bottom: 8px;
                margin-bottom: 15px;
            }

            .close {
                right: 15px;
                top: 10px;
                font-size: 24px;
            }
        }

        /* Thêm animation mượt mà cho mobile */
        @media screen and (max-width: 768px) {
            @keyframes modalFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .modal {
                background-color: rgba(0,0,0,0.6);
                padding: 0 10px;
            }
        }

        .error-message {
            display: block;
            color: red;
            font-size: 12px;
            margin-top: 5px;
            font-style: italic;
            display: none; /* Ẩn message ban đầu */
        }

        /* Chỉ hiển thị border đỏ khi input đã được focus và invalid */
        .form_group input:focus:invalid {
            border-color: red;
        }

        /* Hiển thị error message khi input đã được touched */
        .form_group input.touched:invalid {
            border-color: red;
        }

        .form_group input.touched:invalid + .error-message {
            display: block;
        }

        .form_group textarea.touched:invalid {
            border-color: red;
        }

        .form_group textarea.touched:invalid + .error-message {
            display: block;
        }

        /* Thêm style cho remaining characters counter nếu muốn */
        .remaining-chars {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            text-align: right;
        }

        /* Thêm vào phần style hiện có */
        .error-message {
            display: none;
            color: red;
            font-size: 12px;
            margin-top: 5px;
            padding: 5px;
            background-color: #fff;
            border-radius: 3px;
        }

        .form_group input:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .form_group input.error {
            border-color: red;
        }

        .form_group input.valid {
            border-color: #4CAF50;
        }

        /* Style cho thông báo lỗi */
        .error-alert {
            background-color: #fff;
            color: red;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid red;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"></div>
        <ul class="menu">
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="active">
                <a href="user.php">
                    <i class='bx bx-user'></i>
                    <span>User</span>
                </a>
            </li>
            <li>
                <a href="addproduct.php">
                    <i class='bx bx-box'></i>
                    <span>Product</span>
                </a>
            </li>
            <li>
                <a href="analytics.php">
                    <i class='bx bx-pie-chart-alt'></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li>
                <a href="order.php">
                    <i class='bx bx-cart'></i>
                    <span>Order</span>
                </a>
            </li>
            <li class="logout">
                <a href="logout.php">
                    <i class='bx bx-log-out'></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main_content">
        <div class="mobile-toggle">
            <i class='bx bx-menu'></i>
        </div>
        <div class="header_wrapper">
            <h2 class="header_title">User Management</h2>
            <div class="search_box">
                <form method="GET" action="">
                    <input type="text" name="search" 
                           placeholder="Search by username or email"
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class='bx bx-search'></i></button>
                </form>
            </div>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="error-alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo htmlspecialchars($_GET['error']); ?>',
                    confirmButtonText: 'OK'
                });
            </script>
        <?php endif; ?>

        <div class="add_user">
            <h3><?php echo isset($_GET['edit']) ? 'EDIT USER' : 'ADD USER'; ?></h3>
            <form action="save_user.php" method="post">
                <?php if(isset($edit_user)): ?>
                    <input type="hidden" name="id" value="<?php echo $edit_user['id']; ?>">
                <?php endif; ?>
                
                <div class="form_group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" 
                           value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['username']) : ''; ?>"
                           maxlength="25" 
                           oninput="checkUsername(this)"
                           required>
                    <span id="username-message" class="error-message"></span>
                </div>

                <div class="form_group">
                    <label for="fullname">Full Name:</label>
                    <input type="text" id="fullname" name="fullname" 
                           value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['fullname']) : ''; ?>"
                           maxlength="30"
                           oninput="checkFullname(this)"
                           required>
                    <span id="fullname-message" class="error-message"></span>
                </div>

                <div class="form_group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['phone']) : ''; ?>"
                           maxlength="10"
                           oninput="checkPhone(this)"
                           required>
                    <span id="phone-message" class="error-message"></span>
                </div>

                <div class="form_group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" 
                              rows="3" 
                              maxlength="255"
                              oninput="checkAddress(this)"><?php echo isset($edit_user) ? htmlspecialchars($edit_user['address']) : ''; ?></textarea>
                    <span id="address-message" class="error-message"></span>
                </div>

                <div class="form_group">
                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender">
                        <option value="male" <?php echo (isset($edit_user) && $edit_user['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo (isset($edit_user) && $edit_user['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo (isset($edit_user) && $edit_user['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form_group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['email']) : ''; ?>" 
                           required>
                </div>

                <div class="form_group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" 
                           <?php echo !isset($edit_user) ? 'required' : ''; ?>
                           oninput="checkPassword(this)"
                           minlength="6">
                    <span id="password-message" class="error-message"></span>
                </div>
                
                <div class="form_group">
                    <label for="role">Role:</label>
                    <select name="role" id="role">
                        <option value="staff" <?php echo (isset($edit_user) && $edit_user['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                        <option value="customer" <?php echo (isset($edit_user) && $edit_user['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                    </select>
                </div>
                
                <div class="form_group">
                    <label for="status">Status:</label>
                    <select name="status" id="status">
                        <option value="1" <?php echo (isset($edit_user) && $edit_user['status'] == '1') ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo (isset($edit_user) && $edit_user['status'] == '0') ? 'selected' : ''; ?>>Locked</option>
                    </select>
                </div>

                <div class="form_group">
                    <label for="city">City:</label>
                    <select name="city" id="city" required>
                        <option value="">Select city</option>
                        <option value="Hanoi" <?php echo (isset($edit_user) && $edit_user['city'] == 'Hanoi') ? 'selected' : ''; ?>>Hà Nội</option>
                        <option value="HoChiMinh" <?php echo (isset($edit_user) && $edit_user['city'] == 'HoChiMinh') ? 'selected' : ''; ?>>Hồ Chí Minh</option>
                    </select>
                </div>
                
                <div class="form_group">
                    <button type="submit" name="save" class="submit-btn">
                        <?php echo isset($_GET['edit']) ? 'Update' : 'Save'; ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="user_list">
            <h3>Danh sách người dùng</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr data-role="<?php echo $row['role']; ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo $row['role']; ?></td>
                        <td><?php echo $row['status'] == 1 ? 'Active' : 'Locked'; ?></td>
                        <td class="action-buttons">
                            <a href="#" class="btn btn-view" onclick="viewUser(<?php echo $row['id']; ?>)">
                                <i class='bx bx-show'></i>
                                View
                            </a>
                            <a href="user.php?edit=<?php echo $row['id']; ?>" class="btn btn-edit">
                                <i class='bx bx-edit-alt'></i>
                                Edit
                            </a>
                            <a href="delete_user.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class='bx bx-trash'></i>
                                Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="userModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>User Information</h2>
            <div id="userInfo">
                <!-- User info will be loaded here -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
document.querySelector('form').addEventListener('submit', function(e) {
    const passwordInput = document.querySelector('input[name="password"]');
    const isNewUser = !document.querySelector('input[name="id"]'); // Check if adding new user
    
    if (isNewUser && passwordInput.value.length < 6) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Password',
            text: 'Password must be at least 6 characters!',
            confirmButtonText: 'OK'
        });
        passwordInput.focus();
        return false;
    }
    
    // Existing phone validation
    const phoneInput = document.querySelector('input[name="phone"]');
    const phoneNumber = phoneInput.value.replace(/\D/g, '');
    if (phoneNumber.length !== 10) {
        Swal.fire({
            icon: 'error',
            title: 'Invalid Phone Number',
            text: 'Phone number must be exactly 10 digits!',
            confirmButtonText: 'OK'
        });
        phoneInput.focus();
        return false;
    }
});

function checkPhone(input) {
    const messageElement = document.getElementById('phone-message');
    const value = input.value.replace(/\D/g, ''); // Remove non-digits
    
    if (input.value !== '') {
        input.classList.add('touched');
    }
    
    if (value.length > 10) {
        input.value = value.slice(0, 10);
        messageElement.style.display = 'block';
        messageElement.textContent = 'Phone number must be exactly 10 digits!';
    } else if (value.length < 10 && value.length > 0) {
        messageElement.style.display = 'block';
        messageElement.textContent = `Please enter ${10 - value.length} more digit(s)`;
    } else if (value.length === 10) {
        messageElement.style.display = 'block';
        messageElement.textContent = 'Valid phone number';
        messageElement.style.color = 'green';
    } else {
        messageElement.style.display = 'none';
    }
}

function checkUsername(input) {
    const messageElement = document.getElementById('username-message');
    const maxLength = 25;
    
    if (input.value !== '') {
        input.classList.add('touched');
    }
    
    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
        messageElement.style.display = 'block';
        messageElement.textContent = 'Username cannot exceed 25 characters!';
    } else if (input.value.length === maxLength) {
        messageElement.style.display = 'block';
        messageElement.textContent = 'Username has reached maximum length!';
    } else {
        messageElement.style.display = 'none';
    }
}

function checkFullname(input) {
    const messageElement = document.getElementById('fullname-message');
    const maxLength = 30;
    
    if (input.value !== '') {
        input.classList.add('touched');
    }
    
    if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
        messageElement.style.display = 'block';
        messageElement.textContent = 'Full name cannot exceed 30 characters!';
    } else if (input.value.length === maxLength) {
        messageElement.style.display = 'block';
        messageElement.textContent = 'Full name has reached maximum length!';
    } else {
        messageElement.style.display = 'none';
    }
}

function checkPassword(input) {
    const messageElement = document.getElementById('password-message');
    const minLength = 6;
    
    if (input.value !== '') {
        input.classList.add('touched');
    }
    
    if (input.value.length > 0 && input.value.length < minLength) {
        messageElement.style.display = 'block';
        messageElement.style.color = 'red';
        messageElement.textContent = `Password must be at least ${minLength} characters!`;
    } else if (input.value.length >= minLength) {
        messageElement.style.display = 'block';
        messageElement.style.color = 'green';
        messageElement.textContent = 'Valid password';
    } else {
        messageElement.style.display = 'none';
    }
}

// Add event listeners for all inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form_group input, .form_group textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value !== '') {
                this.classList.add('touched');
            }
        });
        
        input.addEventListener('focus', function() {
            this.classList.remove('touched');
        });
    });
});

// Get modal
var modal = document.getElementById("userModal");
var span = document.getElementsByClassName("close")[0];

// Function to view user details
function viewUser(id) {
    fetch(`get_user_info.php?id=${id}`)
        .then(response => response.json())
        .then(user => {
            const userInfo = document.getElementById('userInfo');
            userInfo.innerHTML = `
                <p><strong>Username:</strong> <span>${escapeHtml(user.username)}</span></p>
                <p><strong>Full Name:</strong> <span>${escapeHtml(user.fullname)}</span></p>
                <p><strong>Email:</strong> <span>${escapeHtml(user.email)}</span></p>
                <p><strong>Phone:</strong> <span>${escapeHtml(user.phone)}</span></p>
                <p><strong>Address:</strong> <span>${escapeHtml(user.address)}</span></p>
                <p><strong>City:</strong> <span>${escapeHtml(user.city)}</span></p>
                <p><strong>Gender:</strong> <span>${escapeHtml(user.gender)}</span></p>
                <p><strong>Role:</strong> <span>${escapeHtml(user.role)}</span></p>
                <p><strong>Status:</strong> <span>${user.status == 1 ? 'Active' : 'Locked'}</span></p>
                <p><strong>Password:</strong> <span>${escapeHtml(user.original_password)}</span></p>
            `;
            modal.style.display = "block";
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading user information');
        });
}

// Function to escape HTML special characters
function escapeHtml(unsafe) {
    return unsafe
        ? unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;")
        : '';
}

// Close modal when clicking (x)
span.onclick = function() {
    modal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Add these functions to your existing JavaScript
async function checkDuplicate(field, value) {
    const id = document.querySelector('input[name="id"]')?.value;
    const response = await fetch(`check_duplicates.php?field=${field}&value=${value}${id ? '&id=' + id : ''}`);
    const data = await response.json();
    return data.exists;
}

// Thay thế đoạn code validateField cũ bằng:
async function validateField(input, field) {
    const messageElement = document.getElementById(`${field}-message`);
    const value = input.value.trim();
    
    if(value) {
        const isDuplicate = await checkDuplicate(field, value);
        if(isDuplicate) {
            messageElement.style.display = 'block';
            messageElement.style.color = 'red';
            messageElement.textContent = `This ${field} already exists!`;
            input.style.borderColor = 'red';
            return false;
        } else {
            messageElement.style.display = 'none';
            input.style.borderColor = '#4CAF50';
            return true;
        }
    }
    return true;
}

// Cập nhật form submit handler
document.querySelector('form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    
    const isUsernameValid = await validateField(username, 'username');
    const isEmailValid = await validateField(email, 'email');
    
    if(!isUsernameValid || !isEmailValid) {
        const errors = [];
        if(!isUsernameValid) errors.push('Username');
        if(!isEmailValid) errors.push('Email');
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: `${errors.join(' and ')} already exists!`,
            confirmButtonText: 'OK'
        });
        return;
    }
    
    this.submit();
});

// Add blur event listeners for real-time validation
document.getElementById('username').addEventListener('blur', function() {
    validateField(this, 'username');
});

document.getElementById('email').addEventListener('blur', function() {
    validateField(this, 'email');
});
    </script>
    <script src="../js/main.js"></script>
</body>
</html>