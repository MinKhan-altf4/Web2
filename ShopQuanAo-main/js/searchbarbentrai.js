


function filterByPrice(min, max) {
  // Chuyển đổi giá thành số để so sánh
  const filteredProducts = products.filter((product) => {
    const price = parseFloat(product.price.replace("$", ""));
    return price >= min && price <= max;
  });

  // Hiển thị các sản phẩm lọc được
  displayProducts(filteredProducts);
}

function displayProducts(productsToDisplay) {
  const productListDiv = document.getElementById("product-list");
  productListDiv.innerHTML = ""; // Xóa nội dung cũ trước khi hiển thị sản phẩm mới

  productsToDisplay.forEach((product) => {
    const productHTML = `
      <div class="product">
        <img src="${product.image}" alt="${product.name}">
        <h3><a href="${product.link}">${product.name}</a></h3>
        <p>${product.price}</p>
      </div>
    `;
    productListDiv.innerHTML += productHTML;
  });
}
