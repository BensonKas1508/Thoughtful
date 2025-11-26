-- ======================================================
--  INSERT SAMPLE USERS (VENDORS)
--  Default password = 123456
-- ======================================================

INSERT INTO users (name, email, password_hash, role, phone)
VALUES 
('Ama Gifts', 'ama@vendor.com', 
'$2y$10$Ebg2xtO6uD8jz.tFZTICl.YiJSm5ilPGCbcxqUWLZaI01CqWDgWnG',
'vendor', '0551234567'),

('Kofi Hampers', 'kofi@vendor.com', 
'$2y$10$Ebg2xtO6uD8jz.tFZTICl.YiJSm5ilPGCbcxqUWLZaI01CqWDgWnG',
'vendor', '0552345678'),

('Nana Crafts', 'nana@vendor.com', 
'$2y$10$Ebg2xtO6uD8jz.tFZTICl.YiJSm5ilPGCbcxqUWLZaI01CqWDgWnG',
'vendor', '0553456789');

-- ======================================================
--  INSERT VENDORS (LINKED TO USERS ABOVE)
-- ======================================================

INSERT INTO vendors (user_id, business_name, address)
VALUES
(1, 'Ama Gifts', 'Accra - East Legon'),
(2, 'Kofi Hampers', 'Tema - Community 22'),
(3, 'Nana Crafts', 'Kumasi - Ahodwo');

-- ======================================================
--  INSERT CATEGORIES
-- ======================================================

INSERT INTO categories (name)
VALUES
('Gift Boxes'),
('Hampers'),
('Flowers'),
('Chocolates'),
('Personalized Items'),
('Birthday Specials');

-- ======================================================
--  INSERT SAMPLE PRODUCTS
-- ======================================================

INSERT INTO products (vendor_id, category_id, name, description, price, stock)
VALUES
(1, 1, 'Luxury Gift Box', 'Premium curated gift box with lavender theme.', 250.00, 12),
(1, 2, 'Sweet Surprise Hamper', 'Basket of sweets and chocolates.', 180.00, 8),
(2, 3, 'Rose Bouquet', 'Fresh roses wrapped beautifully in lavender paper.', 90.00, 20),
(2, 4, 'Chocolate Deluxe Pack', 'High-end chocolates perfect for gifting.', 110.00, 15),
(3, 5, 'Personalized Mug', 'Customizable ceramic mug.', 60.00, 50),
(3, 6, 'Birthday Spark Box', 'Special birthday gift collection.', 200.00, 10);

-- ======================================================
--  INSERT PRODUCT IMAGES (PLACEHOLDER URLs)
-- ======================================================

INSERT INTO product_images (product_id, url)
VALUES
(1, 'https://via.placeholder.com/350x350.png?text=Luxury+Gift+Box'),
(2, 'https://via.placeholder.com/350x350.png?text=Sweet+Hamper'),
(3, 'https://via.placeholder.com/350x350.png?text=Rose+Bouquet'),
(4, 'https://via.placeholder.com/350x350.png?text=Chocolates'),
(5, 'https://via.placeholder.com/350x350.png?text=Custom+Mug'),
(6, 'https://via.placeholder.com/350x350.png?text=Birthday+Spark+Box');

-- DONE SEEDING
