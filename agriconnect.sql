-- Database: agriconnect
CREATE DATABASE IF NOT EXISTS agriconnect;
USE agriconnect;

-- Table: farmers
CREATE TABLE IF NOT EXISTS farmers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    crop VARCHAR(100),
    quantity INT DEFAULT 0,
    price DECIMAL(10,2) DEFAULT 0,
    image VARCHAR(255) DEFAULT 'images/default-crop.jpg'
);

-- Table: purchases
CREATE TABLE IF NOT EXISTS purchases (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(100),
    quantity INT,
    total_price DECIMAL(10,2),
    purchase_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Farmer Data
INSERT INTO farmers (name, crop, quantity, price, image) VALUES
('Ramesh', 'Tomatoes', 100, 25.00, 'images/default-crop.jpg'),
('Sita', 'Wheat', 200, 30.00, 'images/default-crop.jpg'),
('Anand', 'Rice', 150, 40.00, 'images/default-crop.jpg'),
('Meena', 'Potatoes', 300, 20.00, 'images/default-crop.jpg'),
('Vijay', 'Onions', 180, 22.00, 'images/default-crop.jpg');

-- Sample Purchase Data (optional)
INSERT INTO purchases (crop_name, quantity, total_price) VALUES
('Tomatoes', 20, 500.00),
('Wheat', 50, 1500.00),
('Rice', 30, 1200.00);
