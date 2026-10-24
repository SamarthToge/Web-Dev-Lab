CREATE DATABASE IF NOT EXISTS `lightbill`;
USE `lightbill`;

DROP TABLE IF EXISTS `electricity_bill`;

CREATE TABLE `electricity_bill` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_name` VARCHAR(100) NOT NULL,
    `address` TEXT NOT NULL,
    `mobile` VARCHAR(15) NOT NULL,
    `bill_month` VARCHAR(20) NOT NULL,
    `units` INT NOT NULL,
    `rate` DECIMAL(5,2) NOT NULL,
    `total_bill` DECIMAL(10,2) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
