// Hàm đăng ký người dùng
function registerUser() {
  const username = document.getElementById("registerUsername");
  const password = document.getElementById("registerPassword");
  const email = document.getElementById("registerEmail");
  const phone = document.getElementById("registerPhone");
  const address = document.getElementById("registerAddress");
  const fullname = document.getElementById("registerFullname");

  if (username && password && email && phone && address && fullname) {
    if (
      username.value &&
      password.value &&
      email.value &&
      phone.value &&
      address.value &&
      fullname.value
    ) {
      localStorage.setItem("username", username.value);
      localStorage.setItem("password", password.value);
      localStorage.setItem("email", email.value);
      localStorage.setItem("phone", phone.value);
      localStorage.setItem("address", address.value);
      localStorage.setItem("fullname", fullname.value);
      localStorage.setItem("loggedInUser", username.value);
      localStorage.setItem("isLoggedIn", "true");

      alert("Đăng ký thành công!");
      window.location.href = "login.php";
    } else {
      alert("Vui lòng điền đầy đủ thông tin!");
    }
  } else {
    console.error("Không tìm thấy trường dữ liệu!");
  }
}

// Xử lý đăng nhập
function loginUser() {
  const username = document.getElementById("loginUsername").value;
  const password = document.getElementById("password").value;

  const storedUsername = localStorage.getItem("username");
  const storedPassword = localStorage.getItem("password");

  if (username === storedUsername && password === storedPassword) {
    alert("Đăng nhập thành công!");
    localStorage.setItem("isLoggedIn", "true");
    localStorage.setItem("loggedInUser", username);
    window.location.href = "index.php";
  } else {
    alert("Tên đăng nhập hoặc mật khẩu không đúng!");
  }
}

// Cập nhật trạng thái giao diện người dùng
function checkLoginStatus() {
  const isLoggedIn = localStorage.getItem("isLoggedIn") === "true";
  const user = localStorage.getItem("loggedInUser");
  const loginButton = document.querySelector("#userMenu");

  if (isLoggedIn && user && loginButton) {
    loginButton.innerHTML = `
      <div class="user-menu">
        <span>${user}</span>
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
        <a href="contact.php">SUPPORT</a>
      `;
    } else {
      offcanvasLinks.innerHTML = `
        <a href="login.php">Sign in</a>
        <a href="contact.php">SUPPORT</a>
      `;
    }
  }
});
