// cart.js - Đã cập nhật để sử dụng total_price từ cơ sở dữ liệu

/**
 * Kiểm tra trạng thái đăng nhập người dùng
 * @returns {Promise<boolean>} Trạng thái đăng nhập
 */
async function isUserLoggedIn() {
  try {
    const response = await fetch('php/check_login.php', {
      credentials: 'include',  // Đảm bảo cookies session được gửi đi
      cache: 'no-store'  // Tránh cache kết quả
    });
    
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    
    const data = await response.json();
    return data.isLoggedIn === true;  // Đảm bảo kết quả là boolean
  } catch (error) {
    console.error('Lỗi kiểm tra đăng nhập:', error);
    return false;
  }
}

/**
 * Gọi API giỏ hàng với xử lý timeout và lỗi
 * @param {string} action - Hành động API (add, get, update, remove)
 * @param {Object} data - Dữ liệu gửi đến API
 * @returns {Promise<Object>} Kết quả từ API
 */
async function callCartAPI(action, data = {}) {
  try {
    // Thiết lập timeout cho API calls
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 8000); // Tăng timeout lên 8s
    
    const response = await fetch(`php/cart-api.php?action=${action}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Cache-Control': 'no-cache'  // Tránh cache
      },
      body: JSON.stringify(data),
      credentials: 'include',
      signal: controller.signal
    });
    
    clearTimeout(timeoutId);
    
    // Kiểm tra response có phải JSON không
    const contentType = response.headers.get('content-type');
    if (!contentType || !contentType.includes('application/json')) {
      const text = await response.text();
      console.error('Phản hồi không phải JSON:', text);
      // Log thêm thông tin để debug
      console.error('URL gọi:', `php/cart-api.php?action=${action}`);
      console.error('Dữ liệu gửi đi:', data);
      throw new Error('Định dạng phản hồi không hợp lệ');
    }
    
    if (!response.ok) {
      const errorResult = await response.json();
      throw new Error(errorResult.message || `Lỗi HTTP! Trạng thái: ${response.status}`);
    }
    
    const result = await response.json();
    return result;
  } catch (error) {
    if (error.name === 'AbortError') {
      console.error('Timeout khi gọi API');
      throw new Error('Hết thời gian yêu cầu');
    }
    console.error('Lỗi API:', error);
    return { success: false, message: error.message || 'Lỗi kết nối' };
  }
}

/**
 * Lấy thông tin giỏ hàng từ server
 * @returns {Promise<Array>} Mảng các sản phẩm trong giỏ hàng
 */
async function getCart() {
  try {
    const response = await callCartAPI('get');
    if (!response.success) {
      console.error('Lỗi lấy giỏ hàng:', response.message);
    }
    return response.success && Array.isArray(response.cart) ? response.cart : [];
  } catch (error) {
    console.error('Lỗi khi lấy giỏ hàng:', error);
    return [];
  }
}

/**
 * Thêm sản phẩm vào giỏ hàng
 * @param {Object} product - Thông tin sản phẩm cần thêm
 * @returns {Promise<boolean>} Kết quả thêm sản phẩm
 */
async function addToCart(product) {
  try {
    if (!product || !product.id) {
      console.error('Thiếu thông tin sản phẩm');
      return false;
    }

    // Kiểm tra đăng nhập trước khi thêm vào giỏ hàng
    const loggedIn = await isUserLoggedIn();
    
    if (!loggedIn) {
      alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
      window.location.href = 'login.php';
      return false;
    }
    
    // Gọi API thêm vào giỏ hàng
    const response = await callCartAPI('add', {
      product_id: parseInt(product.id) // Đảm bảo product_id là số
      // Không cần gửi quantity vì mặc định là 1
    });
    
    if (response.success) {
      alert(`${product.name} đã được thêm vào giỏ hàng!`);
      await updateCartUI(); // Cập nhật UI giỏ hàng
      return true;
    } else {
      throw new Error(response.message || 'Có lỗi xảy ra');
    }
  } catch (error) {
    console.error('Lỗi khi thêm vào giỏ hàng:', error);
    alert(error.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng');
    return false;
  }
}

/**
 * Cập nhật số lượng sản phẩm trong giỏ hàng
 * @param {number} productId - ID của sản phẩm
 * @param {number} newQuantity - Số lượng mới
 * @returns {Promise<boolean>} Kết quả cập nhật
 */
async function updateQuantity(productId, newQuantity) {
  try {
    if (isNaN(productId) || isNaN(newQuantity)) {
      console.error('Dữ liệu không hợp lệ:', { productId, newQuantity });
      return false;
    }
    
    if (newQuantity < 1) {
      console.log('Số lượng không được nhỏ hơn 1');
      return false;
    }
    
    const response = await callCartAPI('update', {
      product_id: parseInt(productId),
      quantity: parseInt(newQuantity)
    });
    
    if (response.success) {
      await updateCartUI();
      return true;
    } else {
      throw new Error(response.message || 'Có lỗi xảy ra');
    }
  } catch (error) {
    console.error('Lỗi khi cập nhật số lượng:', error);
    alert('Có lỗi xảy ra khi cập nhật số lượng');
    return false;
  }
}

/**
 * Debounce function để tránh gọi API liên tục
 * @param {Function} func - Hàm cần debounce
 * @param {number} wait - Thời gian chờ (ms)
 * @returns {Function} Hàm đã được debounce
 */
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Tạo phiên bản debounce của hàm updateQuantity
const debouncedUpdateQuantity = debounce(updateQuantity, 500);

/**
 * Xóa sản phẩm khỏi giỏ hàng
 * @param {number} productId - ID của sản phẩm cần xóa
 * @returns {Promise<boolean>} Kết quả xóa sản phẩm
 */
async function removeFromCart(productId) {
  try {
    if (isNaN(productId)) {
      console.error('ID sản phẩm không hợp lệ:', productId);
      return false;
    }
    
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
      return false;
    }
    
    const response = await callCartAPI('remove', {
      product_id: parseInt(productId)
    });
    
    if (response.success) {
      await updateCartUI();
      return true;
    } else {
      throw new Error(response.message || 'Có lỗi xảy ra');
    }
  } catch (error) {
    console.error('Lỗi khi xóa sản phẩm:', error);
    alert('Có lỗi xảy ra khi xóa sản phẩm');
    return false;
  }
}

// Alias cho removeFromCart để tương thích ngược với mã cũ
const removeCartItem = removeFromCart;

/**
 * Cập nhật giao diện giỏ hàng
 * @returns {Promise<void>}
 */
async function updateCartUI() {
  const cartTableBody = document.getElementById("cartItems");
  if (!cartTableBody) {
    console.log('Không tìm thấy phần tử cartItems, có thể không phải trang giỏ hàng');
    await updateCartCount(); // Chỉ cập nhật số lượng nếu không có bảng giỏ hàng
    return;
  }
  
  try {
    cartTableBody.classList.add('loading'); // Thêm class loading
    const response = await callCartAPI('get');
    
    if (!response.success) {
      throw new Error(response.message || 'Không thể lấy thông tin giỏ hàng');
    }
    
    const cart = Array.isArray(response.cart) ? response.cart : [];
    
    // Cập nhật số lượng trên icon giỏ hàng
    await updateCartCount(cart);
    
    // Tham chiếu đến phần tổng tiền
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
    cartTableBody.innerHTML = cart.map(item => {
      // Xác thực dữ liệu trước khi hiển thị
      const productId = parseInt(item.product_id) || 0;
      const quantity = parseInt(item.quantity) || 1;
      const price = parseFloat(item.price) || 0;
      // Sử dụng total_price từ cơ sở dữ liệu nếu có, ngược lại tính
      const totalPrice = item.total_price ? 
        parseFloat(item.total_price).toFixed(2) : 
        (price * quantity).toFixed(2);
      const imagePath = item.image ? `img/product/${item.image}` : 'img/product/default.jpg';
      
      return `
      <tr data-id="${productId}">
        <td class="product__cart__item">
          <div class="product__cart__item__pic">
            <img src="${imagePath}" alt="${item.name}" style="width: 90px; height: 90px;" onerror="this.src='img/product/default.jpg'">
          </div>
          <div class="product__cart__item__text">
            <h6>${item.name || 'Sản phẩm không xác định'}</h6>
            <h5>$${price.toFixed(2)}</h5>
          </div>
        </td>
        <td class="quantity__item">
          <div class="quantity">
            <div class="pro-qty">
              <span class="dec qtybtn" onclick="debouncedUpdateQuantity(${productId}, ${quantity - 1})">-</span>
              <input type="text" value="${quantity}" data-id="${productId}" readonly>
              <span class="inc qtybtn" onclick="debouncedUpdateQuantity(${productId}, ${quantity + 1})">+</span>
            </div>
          </div>
        </td>
        <td class="cart__price">$${totalPrice}</td>
        <td class="cart__close">
          <i class="fa fa-close" onclick="removeFromCart(${productId})"></i>
        </td>
      </tr>
    `;
    }).join('');
    
    // Tính tổng tiền từ total_price cơ sở dữ liệu
    const total = cart.reduce((sum, item) => {
      // Sử dụng total_price từ cơ sở dữ liệu nếu có, ngược lại tính theo cách cũ
      const itemTotal = item.total_price ? 
        parseFloat(item.total_price) : 
        ((parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0));
      return sum + itemTotal;
    }, 0);
    
    if (cartTotal) cartTotal.textContent = `$${total.toFixed(2)}`;
    if (cartSubtotal) cartSubtotal.textContent = `$${total.toFixed(2)}`;
    
  } catch (error) {
    console.error('Lỗi khi cập nhật UI giỏ hàng:', error);
   
  } finally {
    cartTableBody.classList.remove('loading');
  }
}

/**
 * Chỉ cập nhật số lượng sản phẩm trên icon giỏ hàng
 * @param {Array} cart - Dữ liệu giỏ hàng (tùy chọn)
 */
async function updateCartCount(cart = null) {
  try {
    if (!cart) {
      const response = await callCartAPI('get');
      cart = response.success ? response.cart : [];
    }
    
    const totalItems = Array.isArray(cart) ? 
      cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0) : 0;
    
    document.querySelectorAll(".cart-count").forEach(span => {
      span.textContent = totalItems;
    });
  } catch (error) {
    console.error('Lỗi khi cập nhật số lượng giỏ hàng:', error);
  }
}

// Đảm bảo trang đã tải hoàn chỉnh trước khi thêm sự kiện
document.addEventListener("DOMContentLoaded", function() {
  // Xử lý sự kiện tăng/giảm số lượng sản phẩm
  document.addEventListener("click", async function(e) {
    if (e.target.classList.contains("qtybtn")) {
      const inputElement = e.target.parentElement.querySelector('input');
      if (!inputElement) return;
      
      const productId = parseInt(inputElement.dataset.id || e.target.closest('tr')?.dataset.id);
      if (!productId) {
        console.error('Không tìm thấy ID sản phẩm');
        return;
      }
      
      let quantity = parseInt(inputElement.value) || 1;
      
      if (e.target.classList.contains("inc")) {
        quantity += 1;
      } else if (e.target.classList.contains("dec") && quantity > 1) {
        quantity -= 1;
      }
      
      if (quantity >= 1) {
        // Trực quan hóa phản hồi ngay lập tức trước khi API trả về
        inputElement.value = quantity;
        try {
          await debouncedUpdateQuantity(productId, quantity);
        } catch (error) {
          // Phục hồi giá trị cũ nếu có lỗi
          console.error('Lỗi cập nhật:', error);
          inputElement.value = quantity > 1 ? quantity - 1 : quantity + 1;
        }
      }
    }
  });

  // Xử lý sự kiện thêm vào giỏ hàng từ trang shop
  document.addEventListener("click", async function(e) {
    if (e.target.classList.contains("add-cart")) {
      e.preventDefault();
      e.stopPropagation(); // Ngăn chặn hành vi mặc định và lan truyền
      
      const productElement = e.target;
      const product = {
        id: parseInt(productElement.dataset.id),
        name: productElement.dataset.name,
        price: parseFloat(productElement.dataset.price),
        image: productElement.dataset.image
      };
      
      // Kiểm tra dữ liệu sản phẩm
      if (!product.id || isNaN(product.id)) {
        console.error('Thiếu hoặc sai ID sản phẩm:', productElement.dataset);
        alert('Thông tin sản phẩm không đầy đủ!');
        return;
      }
      
      // Tạm thời vô hiệu hóa nút để tránh click nhiều lần
      productElement.disabled = true;
      productElement.classList.add('adding');
      
      try {
        await addToCart(product);
      } finally {
        // Khôi phục nút
        setTimeout(() => {
          productElement.disabled = false;
          productElement.classList.remove('adding');
        }, 1000);
      }
    }
  });

  // Sử dụng retryOperation để tăng độ tin cậy
  retryOperation(() => updateCartUI())
    .catch(error => {
      console.error('Không thể tải giỏ hàng sau nhiều lần thử:', error);
      // Cập nhật UI để thông báo lỗi nếu cần thiết
    });
});

// Thêm xử lý lỗi mạng và retry
async function retryOperation(operation, maxRetries = 3) {
  let retries = 0;
  let lastError;
  
  while (retries < maxRetries) {
    try {
      return await operation();
    } catch (error) {
      lastError = error;
      retries++;
      if (retries >= maxRetries) break;
      console.log(`Thử lại lần ${retries}...`);
      await new Promise(resolve => setTimeout(resolve, 1000 * retries));
    }
  }
  
  console.error(`Không thể hoàn thành sau ${maxRetries} lần thử:`, lastError);
  throw lastError;
}

// Hàm thanh toán giỏ hàng
async function checkoutCart() {
  try {
    // Kiểm tra đăng nhập
    const loggedIn = await isUserLoggedIn();
    
    if (!loggedIn) {
      alert("Vui lòng đăng nhập để thanh toán!");
      window.location.href = 'login.php';
      return false;
    }
    
    // Kiểm tra giỏ hàng có trống không
    const cart = await getCart();
    if (!Array.isArray(cart) || cart.length === 0) {
      alert("Giỏ hàng trống, vui lòng thêm sản phẩm trước khi thanh toán!");
      return false;
    }
    
    // Chuyển hướng đến trang thanh toán
    window.location.href = 'checkout.php';
    return true;
  } catch (error) {
    console.error('Lỗi khi thanh toán:', error);
    alert('Có lỗi xảy ra khi xử lý thanh toán');
    return false;
  }
}