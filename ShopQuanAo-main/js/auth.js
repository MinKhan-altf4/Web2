// Hàm đăng ký người dùng
function registerUser() {
  const formData = new FormData();
  formData.append(
    "username",
    document.getElementById("registerUsername").value
  );
  formData.append("email", document.getElementById("registerEmail").value);
  formData.append(
    "password",
    document.getElementById("registerPassword").value
  );
  formData.append(
    "fullname",
    document.getElementById("registerFullname").value
  );
  formData.append("phone", document.getElementById("registerPhone").value);
  formData.append("address", document.getElementById("registerAddress").value);
  formData.append(
    "gender",
    document.querySelector('input[name="gender"]:checked').value
  );

  fetch("php/register_process.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        alert(data.message);
        window.location.href = "login.php";
      } else {
        alert(data.message);
      }
    });
}

// Xử lý đăng nhập
function loginUser() {
  const formData = new FormData();
  formData.append("username", document.getElementById("loginUsername").value);
  formData.append("password", document.getElementById("password").value);
  formData.append("remember", document.getElementById("remember-me").checked);

  fetch("php/login_process.php", {
    method: "POST",
    body: formData,
    credentials: "include", // Use 'include' to ensure cookies work across domains if needed
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Store minimal user info for UI purposes
        sessionStorage.setItem("currentUser", data.username);
        sessionStorage.setItem("userRole", data.role);

        // Redirect based on role
        if (data.role === "admin") {
          window.location.href = "Admin/php/dashboard.php";
        } else {
          window.location.href = "index.php";
        }
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error("Login error:", error);
      alert("Đăng nhập thất bại. Vui lòng thử lại.");
    });
}

// Kiểm tra đăng nhập không đồng bộ
async function isUserLoggedIn() {
  try {
    const response = await fetch("php/check_login.php", {
      credentials: "include", // Gửi cookies session
    });
    const data = await response.json();
    return data.isLoggedIn;
  } catch (error) {
    console.error("Error checking login status:", error);
    return false;
  }
}

// Cập nhật trạng thái giao diện người dùng
async function checkLoginStatus() {
  try {
    const response = await fetch("php/check_login.php", {
      credentials: "include",
    });
    const data = await response.json();
    const loginButton = document.querySelector("#userMenu");

    if (data.isLoggedIn && data.username && loginButton) {
      // Thêm preventDefault() để ngăn chặn hành vi mặc định của thẻ a
      loginButton.innerHTML = `
                <div class="user-menu">
                    <span style="cursor: default;">${data.username}</span>
                    <ul class="user-options">
                        <li><a href="profile.php" onclick="event.preventDefault(); window.location.href='profile.php'">Profile</a></li>
                        <li><a href="orders.php" onclick="event.preventDefault(); window.location.href='orders.php'">Orders</a></li>
                        <li><button onclick="event.preventDefault(); logout()" style="border:none; background:none; color:white; cursor:pointer;">LOG OUT</button></li>
                    </ul>
                </div>
            `;
      addDropdownStyles();
    } else if (loginButton) {
      // Không tự động redirect về login nếu chưa đăng nhập
      loginButton.innerHTML = `<a href="login.php">Sign in</a>`;
    }
  } catch (error) {
    console.error("Error checking login status:", error);
    // Không redirect khi có lỗi
  }
}

// Thêm style cho dropdown
function addDropdownStyles() {
  const style = document.createElement("style");
  style.textContent = `
        .user-menu {
            position: relative;
            display: inline-block;
        }

        .user-options {
            display: none;
            position: absolute;
            background-color: #333;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 9999;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .user-options li {
            padding: 10px 20px;
            text-align: left;
        }

        .user-options li a,
        .user-options li button {
            color: white;
            text-decoration: none;
            display: block;
            width: 100%;
            font-size: 14px;
        }

        .user-options li:hover {
            background-color: #575757;
        }

        .user-menu:hover .user-options {
            display: block;
        }
    `;
  document.head.appendChild(style);
}

// Đăng xuất
function logout() {
  if (confirm("Bạn có chắc chắn muốn đăng xuất không?")) {
    fetch("php/logout.php", {
      method: "POST", // Use POST for logout
      credentials: "include",
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          // Clear session storage
          sessionStorage.clear();
          alert("Đã đăng xuất thành công!");
          window.location.href = "login.php";
        } else {
          throw new Error(data.message || "Logout failed");
        }
      })
      .catch((error) => {
        console.error("Logout error:", error);
        alert("Đăng xuất thất bại. Vui lòng thử lại.");
      });
  }
}

// Cập nhật offcanvas menu
const offcanvasLinks = document.getElementById("offcanvasLinks");
const loggedInUser = localStorage.getItem("loggedInUser");

if (offcanvasLinks) {
  if (loggedInUser) {
    offcanvasLinks.innerHTML = `
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
                <a href="contact.php">SUPPORT</a>
            `;
  } else {
    offcanvasLinks.innerHTML = `
                <a href="login.php">Sign in</a>
                <a href="contact.php">SUPPORT</a>
            `;
  }
}

// Add auto-hide for success messages
document.addEventListener("DOMContentLoaded", function () {
  const messages = document.querySelectorAll(".message");
  messages.forEach(function (message) {
    if (message.classList.contains("success-message")) {
      setTimeout(function () {
        message.style.opacity = "0";
        setTimeout(function () {
          message.style.display = "none";
        }, 500);
      }, 5000);
    }
  });
});
// Hiển thị hoặc ẩn các trường thanh toán dựa trên phương thức được chọn
document.addEventListener("DOMContentLoaded", function () {
  const paymentMethodInputs = document.querySelectorAll(
    'input[name="payment-method"]'
  );
  const bankTransferForm = document.querySelector(".bank-transfer-form");
  const cardPaymentForm = document.querySelector(".card-payment-form");

  paymentMethodInputs.forEach((input) => {
    input.addEventListener("change", function () {
      if (this.value === "transfer") {
        bankTransferForm.style.display = "block";
        cardPaymentForm.style.display = "none";
      } else if (this.value === "card") {
        bankTransferForm.style.display = "none";
        cardPaymentForm.style.display = "block";
      } else {
        bankTransferForm.style.display = "none";
        cardPaymentForm.style.display = "none";
      }
    });
  });
});

// Thêm vào cuối file
document.addEventListener('DOMContentLoaded', function() {
    // Ngăn chặn hành vi mặc định của dropdown menu
    document.addEventListener('click', function(e) {
        if(e.target.closest('.user-menu')) {
            e.preventDefault();
            const dropdownMenu = e.target.closest('.user-menu').querySelector('.user-options');
            if(dropdownMenu) {
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            }
        }
    });
});
