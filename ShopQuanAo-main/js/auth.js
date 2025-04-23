// Hàm đăng ký người dùng
function registerUser() {
    const formData = new FormData();
    formData.append('username', document.getElementById('registerUsername').value);
    formData.append('email', document.getElementById('registerEmail').value);
    formData.append('password', document.getElementById('registerPassword').value);
    formData.append('fullname', document.getElementById('registerFullname').value);
    formData.append('phone', document.getElementById('registerPhone').value);
    formData.append('address', document.getElementById('registerAddress').value);
    formData.append('gender', document.querySelector('input[name="gender"]:checked').value);

    fetch('php/register_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            alert(data.message);
            window.location.href = 'login.php';
        } else {
            alert(data.message);
        }
    });
}

// Xử lý đăng nhập
function loginUser() {
    const formData = new FormData();
    formData.append('username', document.getElementById('loginUsername').value);
    formData.append('password', document.getElementById('password').value);
    formData.append('remember', document.getElementById('remember-me').checked);

    fetch('php/login_process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            if(data.role === 'admin') {
                window.location.href = 'Admin/php/dashboard.php';
            } else {
                window.location.href = 'index.php';
            }
        } else {
            alert(data.message);
        }
    });
}

// Cập nhật trạng thái giao diện người dùng
function checkLoginStatus() {
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
  const user = localStorage.getItem("loggedInUser");
  const loginButton = document.querySelector("#userMenu");

  if (isLoggedIn && user && loginButton) {
    loginButton.innerHTML = `
    <div class="user-menu">
      <span style="cursor: default;" onclick="event.preventDefault()">${user}</span>
      <ul class="user-options">
        <li><a href="profile.php">Profile</a></li>
        <li><a href="history.php">History</a></li>
        <li><button onclick="logout()" style="border:none; background:none; color:white; cursor:pointer;">LOG OUT</button></li>
      </ul>
    </div>
  `;
  
    addDropdownStyles();
  } else if (loginButton) {
    loginButton.innerHTML = `<a href="login.php">Sign in</a>`;
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
    localStorage.removeItem("isLoggedIn");
    localStorage.removeItem("loggedInUser");
    alert("Đã đăng xuất thành công!");
    window.location.href = "login.php";
  }
}

// Hiển thị đơn hàng gần nhất
document.addEventListener("DOMContentLoaded", function () {
  checkLoginStatus();

  const orderHistory = JSON.parse(localStorage.getItem("orderHistory")) || [];
  const cartTableBody = document.querySelector("#cart-table tbody");

  if (cartTableBody) {
    if (orderHistory.length === 0) {
      cartTableBody.innerHTML = `<tr><td colspan="4" class="text-center">Không có đơn hàng nào.</td></tr>`;
    } else {
      const latestOrder = orderHistory[orderHistory.length - 1];
      const { recipient, phone, address, paymentMethod, totalPrice, items } = latestOrder;

      document.getElementById("recipient").textContent = recipient;
      document.getElementById("phone").textContent = phone;
      document.getElementById("address").textContent = address;
      document.getElementById("payment-method").textContent = paymentMethod;
      document.getElementById("total").textContent = `$${totalPrice.toFixed(2)}`;

      cartTableBody.innerHTML = items.map(item => `
        <tr>
          <td class="product__cart__item">
            <div class="product__cart__item__pic">
              <img src="${item.image}" alt="${item.name}" />
            </div>
            <div class="product__cart__item__text">
              <h6>${item.name}</h6>
            </div>
          </td>
          <td class="quantity__item">
            <div class="quantity">
              <span>${item.quantity}</span>
            </div>
          </td>
          <td class="cart__price">$${(item.price * item.quantity).toFixed(2)}</td>
        </tr>`).join("");
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
        <a href="contact.php">SPT</a>
      `;
    } else {
      offcanvasLinks.innerHTML = `
        <a href="login.php">Sign in</a>
        <a href="contact.php">SPT</a>
      `;
    }
  }
});

// Add auto-hide for success messages
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.message');
    messages.forEach(function(message) {
        if (message.classList.contains('success-message')) {
            setTimeout(function() {
                message.style.opacity = '0';
                setTimeout(function() {
                    message.style.display = 'none';
                }, 500);
            }, 5000);
        }
    });
});
