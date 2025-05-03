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
    <link rel="stylesheet" href="../css/accounts.css">
    <link rel="stylesheet" href="../css/grid.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                           required>
                </div>

                <div class="form_group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" 
                           value="<?php echo isset($edit_user) ? htmlspecialchars($edit_user['phone']) : ''; ?>"
                           pattern="[0-9]{10}" 
                           title="Please enter a valid 10-digit phone number"
                           required>
                </div>

                <div class="form_group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" rows="3"><?php echo isset($edit_user) ? htmlspecialchars($edit_user['address']) : ''; ?></textarea>
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
                           <?php echo !isset($edit_user) ? 'required' : ''; ?>>
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

    <script>
    function checkUsername(input) {
        const messageElement = document.getElementById('username-message');
        if (input.value.length > 25) {
            input.value = input.value.slice(0, 25); // Cắt bớt nếu dài quá 12 ký tự
            messageElement.textContent = 'Username không được vượt quá 25 ký tự!';
        } else if (input.value.length === 25) {
            messageElement.textContent = 'Username đã đạt độ dài tối đa!';
        } else {
            messageElement.textContent = '';
        }
    }

    const modal = document.getElementById("userModal");
    const span = document.getElementsByClassName("close")[0];

    function viewUser(userId) {
        fetch(`get_user_info.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("userInfo").innerHTML = `
                    <p><strong>Username:</strong> ${data.username}</p>
                    <p><strong>Full Name:</strong> ${data.fullname}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Phone:</strong> ${data.phone}</p>
                    <p><strong>Address:</strong> ${data.address}</p>
                    <p><strong>Gender:</strong> ${data.gender}</p>
                    <p><strong>Role:</strong> ${data.role}</p>
                    <p><strong>Status:</strong> ${data.status == 1 ? 'Active' : 'Locked'}</p>
                    <p><strong>Password:</strong> ${data.original_password}</p>
                `;
                modal.style.display = "block";
            });
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
</body>
</html>