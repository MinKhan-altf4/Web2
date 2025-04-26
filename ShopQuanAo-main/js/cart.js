// cart.js - Phiên bản sử dụng session thay vì localStorage
// Hàm kiểm tra đăng nhập dựa trên API
async function isUserLoggedIn() {
  try {
    const response = await fetch('php/check_login.php', {
      credentials: 'include'  // Thêm credentials để gửi cookies session
    });
    const data = await response.json();
    return data.isLoggedIn; // Trả về trạng thái đăng nhập từ session
  } catch (error) {
    console.error('Error checking login status:', error);
    return false;
  }
}

// Hàm gọi API giỏ hàng
async function callCartAPI(action, data = {}) {
  try {
    const response = await fetch(`php/cart-api.php?action=${action}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json'
      },
      body: new URLSearchParams(data),
      credentials: 'include'
    });
    
    // Kiểm tra response có phải JSON không
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      const text = await response.text();
      console.error('Non-JSON response:', text);
      throw new Error('Invalid response format');
    }

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    
    try {
      const result = await response.json();
      return result;
    } catch (jsonError) {
      console.error('JSON parse error:', jsonError);
      throw new Error('Invalid JSON response');
    }
  } catch (error) {
    console.error('API Error:', error);
    return { success: false, message: error.message || 'Network error' };
  }
}

// Hàm lấy giỏ hàng từ server
async function getCart() {
  const response = await callCartAPI('get');
  return response.success ? response.cart : [];
}

// Hàm thêm sản phẩm vào giỏ hàng
async function addToCart(product) {
  try {
    // Kiểm tra đăng nhập
    const loggedIn = await isUserLoggedIn();
    
    if (!loggedIn) {
      alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
      window.location.href = 'login.php';
      return false;
    }

    // Gọi API thêm vào giỏ hàng
    const response = await fetch('php/cart-api.php?action=add', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        product_id: product.id,
        quantity: 1
      }),
      credentials: 'include'
    });

    const data = await response.json();

    if (data.success) {
      alert(`${product.name} đã được thêm vào giỏ hàng!`);
      await updateCartUI(); // Cập nhật UI giỏ hàng
      return true;
    } else {
      throw new Error(data.message || 'Có lỗi xảy ra');
    }

  } catch (error) {
    console.error('Error adding to cart:', error);
    alert(error.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
    return false;
  }
}

// Hàm cập nhật số lượng sản phẩm
async function updateCartItem(productId, quantity) {
  const response = await callCartAPI('update', {
    product_id: productId,
    quantity: quantity
  });

  if (response.success) {
    await updateCartUI();
    return true;
  } else {
    alert(response.message || 'Có lỗi xảy ra khi cập nhật giỏ hàng');
    return false;
  }
}

// Hàm cập nhật số lượng - sửa để sử dụng callCartAPI chung
async function updateQuantity(productId, newQuantity) {
  try {
    if (newQuantity < 1) return;

    const response = await callCartAPI('update', {
      product_id: productId,
      quantity: newQuantity
    });

    if (response.success) {
      await updateCartUI();
      return true;
    } else {
      throw new Error(response.message || 'Có lỗi xảy ra');
    }
  } catch (error) {
    console.error('Error updating quantity:', error);
    alert('Có lỗi xảy ra khi cập nhật số lượng');
    return false;
  }
}

// Hàm xóa sản phẩm khỏi giỏ hàng - đổi tên thành removeFromCart để khớp với gọi trong HTML
async function removeFromCart(productId) {
  try {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
      return;
    }

    const response = await callCartAPI('remove', {
      product_id: productId
    });

    if (response.success) {
      await updateCartUI();
      return true;
    } else {
      throw new Error(response.message || 'Có lỗi xảy ra');
    }
  } catch (error) {
    console.error('Error removing item:', error);
    alert('Có lỗi xảy ra khi xóa sản phẩm');
    return false;
  }
}

// Alias cho removeFromCart để tương thích ngược
const removeCartItem = removeFromCart;

// Hàm cập nhật giao diện giỏ hàng
async function updateCartUI() {
  try {
    const response = await callCartAPI('get');
    
    if (!response.success) {
      throw new Error(response.message || 'Không thể lấy thông tin giỏ hàng');
    }

    const cart = response.cart;
    
    // Cập nhật số lượng trên icon giỏ hàng
    const totalItems = cart.reduce((sum, item) => sum + parseInt(item.quantity), 0);
    document.querySelectorAll(".cart-count").forEach(span => {
      span.textContent = totalItems;
    });

    // Kiểm tra nếu không phải trang giỏ hàng thì chỉ cập nhật số lượng
    const cartTableBody = document.getElementById("cartItems");
    if (!cartTableBody) return;
    
    const cartSubtotal = document.querySelector("#subtotal");
    const cartTotal = document.querySelector("#total");

    // Xử lý giỏ hàng trống
    if (cart.length === 0) {
      cartTableBody.innerHTML = `
        <tr>
          <td colspan="4" class="text-center">Giỏ hàng trống</td>
        </tr>`;
      if (cartTotal) cartTotal.textContent = "$0.00";
      if (cartSubtotal) cartSubtotal.textContent = "$0.00";
      return;
    }

    // Render sản phẩm trong giỏ hàng
    cartTableBody.innerHTML = cart.map(item => `
      <tr data-id="${item.product_id}">
        <td class="product__cart__item">
          <div class="product__cart__item__pic">
            <img src="img/product/${item.image}" alt="${item.name}" style="width: 90px; height: 90px;">
          </div>
          <div class="product__cart__item__text">
            <h6>${item.name}</h6>
            <h5>$${parseFloat(item.price).toFixed(2)}</h5>
          </div>
        </td>
        <td class="quantity__item">
          <div class="quantity">
            <div class="pro-qty">
              <span class="dec qtybtn" onclick="updateQuantity(${item.product_id}, ${parseInt(item.quantity) - 1})">-</span>
              <input type="text" value="${item.quantity}" readonly>
              <span class="inc qtybtn" onclick="updateQuantity(${item.product_id}, ${parseInt(item.quantity) + 1})">+</span>
            </div>
          </div>
        </td>
        <td class="cart__price">$${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</td>
        <td class="cart__close">
          <i class="fa fa-close" onclick="removeFromCart(${item.product_id})"></i>
        </td>
      </tr>
    `).join('');

    // Tính tổng tiền
    const total = cart.reduce((sum, item) => 
      sum + (parseFloat(item.price) * parseInt(item.quantity)), 0);
    
    if (cartTotal) cartTotal.textContent = `$${total.toFixed(2)}`;
    if (cartSubtotal) cartSubtotal.textContent = `$${total.toFixed(2)}`;

  } catch (error) {
    console.error('Error updating cart UI:', error);
    alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
  }
}

// Xử lý sự kiện trong trang giỏ hàng
document.addEventListener("click", async function (e) {
  // Xử lý tăng/giảm số lượng
  if (e.target.classList.contains("qtybtn")) {
    // Lấy phần tử input số lượng
    const inputElement = e.target.parentElement.querySelector('input');
    const productId = e.target.closest('tr').dataset.id;
    let quantity = parseInt(inputElement.value);
    
    if (e.target.classList.contains("inc")) {
      quantity += 1;
    } else if (e.target.classList.contains("dec") && quantity > 1) {
      quantity -= 1;
    }
    
    if (quantity >= 1) {
      await updateQuantity(productId, quantity);
    }
  }
});

// Xử lý sự kiện thêm vào giỏ hàng từ trang shop
document.addEventListener("click", async function (e) {
  if (e.target.classList.contains("add-cart")) {
    e.preventDefault();
    
    const product = {
      id: e.target.dataset.id,
      name: e.target.dataset.name,
      price: parseFloat(e.target.dataset.price),
      image: e.target.dataset.image
    };

    await addToCart(product);
  }
});

// Kiểm tra và cập nhật giỏ hàng khi trang được tải
document.addEventListener("DOMContentLoaded", function() {
  updateCartUI(); // Tải giỏ hàng khi trang được load
});