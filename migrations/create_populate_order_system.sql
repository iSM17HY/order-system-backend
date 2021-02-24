CREATE TABLE customers (
  id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstname varchar(30) NOT NULL,
  surname varchar(30) NOT NULL
);

CREATE TABLE orders (
  id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  customer_id BIGINT NOT NULL,
  date DATE
);

CREATE TABLE products (
  id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  product_name varchar(50) NOT NULL,
  price DECIMAL(10, 2) NOT NULL
);

CREATE TABLE order_items (
  id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  order_id BIGINT NOT NULL,
  product_id BIGINT NOT NULL,
  quantity INTEGER NOT NULL
);


INSERT INTO customers (firstname, surname) VALUES
('Simon', 'Foster'),
('Chris', 'Dobson'),
('Alex', 'Wyett'),
('Jon', 'Beverley'),
('Chris', 'Dobson');

INSERT INTO products (product_name, price) VALUES
('Hammer', 10.00),
('Saw', 8.00),
('Drill', 15.00),
('Nails', 0.05),
('Screwdriver', 3.00),
('Screws', 0.10),
('Staples', 0.01),
('Stapler', 5.00);

INSERT INTO orders (customer_id, date) VALUES
( 1, '2020-08-01'),
( 2, '2020-08-03'),
( 3, '2020-08-03'),
( 1, '2020-08-06'),
( 2, '2020-08-03'),
( 4, '2020-08-09'),
( 1, '2020-08-15'),
( 2, '2020-08-18'),
( 5, '2020-08-25');

INSERT INTO order_items (order_id, product_id, quantity) VALUES
(1, 1, 1),
(1, 2, 1),
(2, 1, 1),
(3, 2, 1),
(3, 3, 1),
(4, 4, 50),
(5, 4, 100),
(6, 5, 1),
(6, 5, 1),
(6, 6, 10),
(7, 3, 1),
(8, 5, 1),
(8, 6, 20),
(9, 2, 1),
(9, 7, 100),
(9, 8, 1);

