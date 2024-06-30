CREATE DATABASE IF NOT EXISTS db_test_rest;

use db_test_rest;

CREATE TABLE IF NOT EXISTS orders (
    order_id VARCHAR(14) PRIMARY KEY,
    done BOOLEAN DEFAULT(FALSE) NOT NULL
);

CREATE TABLE IF NOT EXISTS products (
    product_id INT PRIMARY KEY AUTO_INCREMENT
);

CREATE TABLE IF NOT EXISTS order_product (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id VARCHAR(15) NOT NULL,
    product_id INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders (order_id),
    FOREIGN KEY (product_id) REFERENCES products (product_id)
);

INSERT INTO orders VALUES ('5to0lk2s8tqqjg', 0), ('j79ldfmsnv216e', 1);
INSERT INTO products VALUES (1), (2), (3), (4);
INSERT INTO order_product (order_id, product_id) 
VALUES ('5to0lk2s8tqqjg', 2), 
       ('5to0lk2s8tqqjg', 3), 
       ('j79ldfmsnv216e', 3), 
       ('j79ldfmsnv216e', 4);