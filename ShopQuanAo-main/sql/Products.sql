CREATE DATABASE fashion_store;
USE fashion_store;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10,2),
    image VARCHAR(255)
);

INSERT INTO products (name, price, image) VALUES
('Áo thun trắng', 199000, 'images/product1.jpg'),
('Quần jean xanh', 399000, 'images/product2.jpg'),
('Giày sneaker trắng', 599000, 'images/product3.jpg');
