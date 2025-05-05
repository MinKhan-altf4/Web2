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
  const username = document.getElementById('loginUsername').value;
  const password = document.getElementById('password').value;

  if (!username || !password) {
    Swal.fire({
      icon: 'warning',
      title: 'Thiếu thông tin',
      text: 'Vui lòng nhập đầy đủ thông tin!',
      confirmButtonText: 'OK'
    });
    
    return;
  }

  fetch('login.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'success') {
      window.location.href = 'index.php';
    } else {
      // Show error message
      const errorDiv = document.createElement('div');
      errorDiv.className = 'error';
      errorDiv.textContent = data.message;
      // Remove any existing error messages
      const existingError = document.querySelector('.error');
      if (existingError) {
        existingError.remove();
      }
      document.getElementById('loginForm').prepend(errorDiv);
    }
  })
  Swal.fire({ icon: 'success', title: data.message, timer: 2000, showConfirmButton: false });
// …
Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Có lỗi xảy ra, vui lòng thử lại sau!', confirmButtonText: 'OK' });

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

    // --- HEADER USER-MENU (chính) ---
    const loginButton = document.querySelector("#userMenu");
    if (data.isLoggedIn && data.username && loginButton) {
      loginButton.innerHTML = `
        <div class="user-menu">
          <span style="cursor: default;">${data.username}</span>
          <ul class="user-options">
            <li><a href="profile.php" onclick="event.preventDefault(); window.location.href='profile.php'">Profile</a></li>
            <li><a href="orders.php" onclick="event.preventDefault(); window.location.href='orders.php'">Orders</a></li>
            <li><button onclick="logout()" style="border:none; background:none; color:white; cursor:pointer;">LOG OUT</button></li>
          </ul>
        </div>
      `;
      addDropdownStyles();
    } else if (loginButton) {
      loginButton.innerHTML = `<a href="login.php">Sign in</a>`;
    }

    // --- OFFCANVAS MENU (mb) ---
    const offcanvasLinks = document.getElementById("offcanvasLinks");
    if (offcanvasLinks) {
      if (data.isLoggedIn && data.username) {
        offcanvasLinks.innerHTML = `
          <a href="profile.php">Profile</a>
          <li><a href="orders.php">Orders</a></li>
          <a href="#" onclick="logout(); return false;">Logout</a>
          <a href="contact.php">Support</a>
        `;
      } else {
        offcanvasLinks.innerHTML = `
          <a href="login.php">Sign in</a>
          <a href="contact.php">Support</a>
        `;
      }
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
  if (confirm("Do you want to log out?")) {
      fetch('php/logout.php', {
          method: 'POST', // Use POST for logout
          credentials: 'include'
      })
      .then(response => response.json())
      .then(data => {
          if (data.status === 'success') {
              // Clear session storage
              sessionStorage.clear();
              alert("Successfully logged out!");
              window.location.href = "login.php";
          } else {
              throw new Error(data.message || 'Logout failed');
          }
      })
      .catch(error => {
          console.error('Logout error:', error);
          alert('Logout failed. Please try again.');
      });
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

document.addEventListener("DOMContentLoaded", () => {
  checkLoginStatus();
});

