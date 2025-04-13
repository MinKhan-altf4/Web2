// cart.js - Phiên bản đã sửa lỗi
document.addEventListener("DOMContentLoaded", function () {
  // Khởi tạo giỏ hàng
  let cart = JSON.parse(localStorage.getItem("cart")) || [];

  // Thêm vào file cart.js
  function updateCartIcon() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

    // Cập nhật tất cả các phần tử hiển thị số lượng giỏ hàng
    document
      .querySelectorAll(
        ".header__nav__option span, .offcanvas__nav__option span"
      )
      .forEach((span) => {
        span.textContent = totalItems;
      });
  }

  // Gọi hàm này mỗi khi giỏ hàng thay đổi (thêm, xóa, cập nhật số lượng)
  // Thêm vào các hàm addToCart, remove, update quantity
  updateCartIcon();
  // Hàm cập nhật toàn bộ giao diện giỏ hàng
  function updateCartUI() {
    const cartTableBody = document.querySelector(
      ".shopping__cart__table tbody"
    );
    const cartSubtotal = document.querySelector("#subtotal");
    const cartTotal = document.querySelector("#total");

    // Xử lý khi giỏ hàng trống
    if (cart.length === 0) {
      if (cartTableBody) {
        cartTableBody.innerHTML = `
          <tr>
            <td colspan="4" class="text-center">Your cart is empty!</td>
          </tr>`;
      }
      if (cartTotal) cartTotal.textContent = "$0.00";
      if (cartSubtotal) cartSubtotal.textContent = "$0.00";
      updateCartIcon();
      return;
    }

    // Render danh sách sản phẩm
    if (cartTableBody) {
      cartTableBody.innerHTML = cart
        .map(
          (item, index) => `
        <tr>
          <td class="product__cart__item">
            <div class="product__cart__item__pic">
              <img src="${item.image}" alt="${item.name}" />
            </div>
            <div class="product__cart__item__text">
              <h6>${item.name}</h6>
              <h5>$${item.price.toFixed(2)}</h5>
            </div>
          </td>
          <td class="quantity__item">
            <div class="quantity">
              <button class="qty-btn" data-action="decrease" data-index="${index}">-</button>
              <span>${item.quantity}</span>
              <button class="qty-btn" data-action="increase" data-index="${index}">+</button>
            </div>
          </td>
          <td class="cart__price">$${(item.price * item.quantity).toFixed(
            2
          )}</td>
          <td class="cart__close">
            <button class="remove-btn" data-index="${index}">×</button>
          </td>
        </tr>
      `
        )
        .join("");
    }

    // Tính tổng tiền
    const total = cart.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    );
    if (cartTotal) cartTotal.textContent = `$${total.toFixed(2)}`;
    if (cartSubtotal) cartSubtotal.textContent = `$${total.toFixed(2)}`;
    updateCartIcon();
  }

  // Hàm thêm sản phẩm vào giỏ hàng
  function addToCart(product) {
    // Chuẩn hóa giá tiền về dạng number
    const price =
      typeof product.price === "string"
        ? parseFloat(product.price.replace("$", ""))
        : product.price;

    // Kiểm tra sản phẩm đã có trong giỏ chưa
    const existingItem = cart.find((item) => item.id === product.id);

    if (existingItem) {
      existingItem.quantity++;
    } else {
      cart.push({
        id: product.id,
        name: product.name,
        price: price,
        image: product.image,
        quantity: 1,
      });
    }

    // Lưu vào localStorage và cập nhật UI
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartUI();

    // Hiển thị thông báo
    alert(`${product.name} đã được thêm vào giỏ hàng!`);
  }

  // Xử lý sự kiện thêm vào giỏ hàng từ trang shop
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("add-cart")) {
      e.preventDefault();
      const productElement = e.target.closest(".product__item");

      const product = {
        id: productElement.querySelector("h6").textContent.trim(),
        name: productElement.querySelector("h6").textContent.trim(),
        price: productElement.querySelector("h5").textContent,
        image: productElement.querySelector(".set-bg").dataset.setbg,
      };

      addToCart(product);
    }
  });

  // Xử lý sự kiện trong trang giỏ hàng
  document.addEventListener("click", function (e) {
    // Xử lý tăng/giảm số lượng
    if (e.target.classList.contains("qty-btn")) {
      const index = e.target.dataset.index;
      const action = e.target.dataset.action;

      if (action === "increase") {
        cart[index].quantity++;
      } else if (action === "decrease" && cart[index].quantity > 1) {
        cart[index].quantity--;
      }

      localStorage.setItem("cart", JSON.stringify(cart));
      updateCartUI();
    }

    // Xử lý xóa sản phẩm
    if (e.target.classList.contains("remove-btn")) {
      const index = e.target.dataset.index;
      if (confirm("Bạn có chắc muốn xóa sản phẩm này?")) {
        cart.splice(index, 1);
        localStorage.setItem("cart", JSON.stringify(cart));
        updateCartUI();
      }
    }
  });

  // Cập nhật UI khi trang được tải
  updateCartUI();

  // Xử lý nút thanh toán
  const checkoutBtn = document.querySelector(".primary-btn");
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", function (e) {
      if (cart.length === 0) {
        e.preventDefault();
        alert("Giỏ hàng trống, vui lòng thêm sản phẩm!");
      }
    });
  }
});
//-----------------------------------------checkout------------------------------------------------------
document.addEventListener("DOMContentLoaded", function () {
  const checkbox = document.getElementById("different-address");
  const placeOrderBtn = document.querySelector(".place-order-btn");

  function updateCheckoutUI() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    const orderList = document.querySelector(".checkout__total__products");
    const totalElement = document.querySelector(
      ".checkout__total__all li:last-child span"
    );
    const subtotalElement = document.querySelector(
      ".checkout__total__all li:first-child span"
    );

    if (cart.length === 0) {
      orderList.innerHTML = "<li>No items in cart</li>";
      subtotalElement.textContent = "$0.00";
      totalElement.textContent = "$0.00";
      return;
    }

    let totalPrice = 0;

    orderList.innerHTML = cart
      .map(
        (item) => `
        <li>${item.quantity} x ${item.name} <span>$${(
          item.price * item.quantity
        ).toFixed(2)}</span></li>
      `
      )
      .join("");

    totalPrice = cart.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    );
    subtotalElement.textContent = `$${totalPrice.toFixed(2)}`;
    totalElement.textContent = `$${totalPrice.toFixed(2)}`;
  }

  placeOrderBtn.addEventListener("click", function () {
    let recipient, phone, address;
    const selectedMethod = document.querySelector(
      'input[name="payment-method"]:checked'
    ).value;

    if (checkbox.checked) {
      recipient = document.getElementById("other-full-name").value;
      phone = document.getElementById("other-phone-number").value;
      address = document.getElementById("other-address").value;
      // Kiểm tra nếu có bất kỳ trường nào bị bỏ trống
      if (!recipient || !phone || !address) {
        alert("Vui lòng nhập đầy đủ thông tin người nhận!");
        return;
      }
    } else {
      recipient = localStorage.getItem("fullname");
      phone = localStorage.getItem("phone");
      address = localStorage.getItem("address");

      if (!recipient || !phone || !address) {
        alert(
          "Không tìm thấy thông tin mặc định. Vui lòng cập nhật thông tin cá nhân."
        );
        return;
      }
    }

    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    const totalPrice = cart.reduce(
      (sum, item) => sum + item.price * item.quantity,
      0
    );

    const orderDetails = {
      recipient,
      phone,
      address,
      paymentMethod: selectedMethod,
      totalPrice,
      items: cart,
      orderDate: new Date().toLocaleString(),
    };

    // Lưu lịch sử đơn hàng
    const orderHistory = JSON.parse(localStorage.getItem("orderHistory")) || [];
    orderHistory.push(orderDetails);
    localStorage.setItem("orderHistory", JSON.stringify(orderHistory));

    alert("Đặt hàng thành công, cảm ơn quý khách!");

    // Xóa giỏ hàng
    localStorage.removeItem("cart");
    window.location.href = "history.php"; // Chuyển đến trang lịch sử đơn hàng
  });

  if (window.location.pathname.includes("checkout.php")) {
    updateCheckoutUI();
  }
  //-------------------------------------Thanh toán
  // Lấy các phần tử liên quan đến phương thức thanh toán
  const paymentMethods = document.querySelectorAll(
    'input[name="payment-method"]'
  );
  const bankTransferForm = document.querySelector(".bank-transfer-form"); // Form cho thanh toán chuyển khoản
  const cardPaymentForm = document.querySelector(".card-payment-form"); // Form cho thanh toán qua thẻ

  // Xử lý hiển thị form phù hợp khi người dùng chọn phương thức thanh toán
  paymentMethods.forEach((method) => {
    method.addEventListener("change", function () {
      if (this.value === "transfer") {
        // Hiển thị form chuyển khoản ngân hàng
        bankTransferForm.style.display = "block";
        cardPaymentForm.style.display = "none";
      } else if (this.value === "card") {
        // Hiển thị form nhập thông tin thẻ
        bankTransferForm.style.display = "none";
        cardPaymentForm.style.display = "block";
      } else {
        // Ẩn cả hai form nếu chọn tiền mặt
        bankTransferForm.style.display = "none";
        cardPaymentForm.style.display = "none";
      }
    });
  });
  // Lấy phương thức thanh toán đã được chọn
  const selectedMethod = document.querySelector(
    'input[name="payment-method"]:checked'
  ).value;

  if (selectedMethod === "transfer") {
    // Kiểm tra xem người dùng đã nhập mã tham chiếu chuyển khoản chưa
    const reference = document.getElementById("bank-reference").value;
    if (!reference) {
      alert("Please provide a reference number for the bank transfer.");
      return;
    }
    alert("Your bank transfer order has been placed!");
  } else if (selectedMethod === "card") {
    // Lấy thông tin thẻ từ form
    const cardNumber = document.getElementById("card-number").value;
    const cardHolder = document.getElementById("card-holder").value;
    const expiryDate = document.getElementById("expiry-date").value;
    const cvv = document.getElementById("cvv").value;
    document
      .getElementById("card-number")
      .addEventListener("input", function (e) {
        const value = e.target.value.replace(/\D/g, ""); // Chỉ giữ lại số
        e.target.value = value.replace(/(\d{4})/g, "$1 ").trim(); // Thêm khoảng trắng sau mỗi 4 số
      });

    document
      .getElementById("expiry-date")
      .addEventListener("input", function (e) {
        const value = e.target.value.replace(/\D/g, ""); // Chỉ giữ lại số
        if (value.length <= 2) {
          e.target.value = value; // Nếu dưới 2 số, không thêm dấu "/"
        } else {
          e.target.value = value.slice(0, 2) + "/" + value.slice(2, 4); // Định dạng MM/YY
        }
      });
  }
});
