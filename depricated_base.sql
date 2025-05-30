-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 16, 2024 at 06:53 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `proj_bank`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `generate_unique_iban` () RETURNS VARCHAR(34) CHARSET utf8mb4 COLLATE utf8mb4_general_ci DETERMINISTIC BEGIN
    DECLARE new_iban VARCHAR(34);
    DECLARE unique_part VARCHAR(24);
    DECLARE is_unique BOOLEAN DEFAULT FALSE;
    DECLARE country_code CHAR(2) DEFAULT 'PL';

    WHILE NOT is_unique DO
        SET unique_part = LPAD(FLOOR(RAND() * 999999999999999999999999), 24, '0'); 
        SET new_iban = CONCAT(country_code, '17369463', unique_part); -- Concatenates the country code, bank code, and unique part
        -- Check if the generated IBAN is unique
        IF NOT EXISTS (SELECT 1 FROM Account WHERE iban = new_iban) THEN
            SET is_unique = TRUE;
        END IF;
    END WHILE;

    RETURN new_iban;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `generate_unique_pin` () RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE new_pin INT;
    DECLARE is_unique BOOLEAN DEFAULT FALSE;

    WHILE NOT is_unique DO
        SET new_pin = FLOOR(10000000 + RAND() * 90000000); -- Generate an 8-digit number
        -- Check if the generated PIN is unique
        IF NOT EXISTS (SELECT 1 FROM Account WHERE pin = new_pin) THEN
            SET is_unique = TRUE;
        END IF;
    END WHILE;

    RETURN new_pin;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE `Account` (
  `acc_id` int(11) NOT NULL,
  `pin` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `iban` varchar(34) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `money` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Account`
--

INSERT INTO `Account` (`acc_id`, `pin`, `password`, `iban`, `cust_id`, `money`) VALUES
(3, 83508919, 'password5', 'PL17369463985487949089601600000000', 1, 5000),
(4, 52942822, 'password6', 'PL17369463429248526160836700000000', 2, 7000);

--
-- Triggers `Account`
--
DELIMITER $$
CREATE TRIGGER `before_account_insert` BEFORE INSERT ON `Account` FOR EACH ROW BEGIN
    SET NEW.pin = generate_unique_pin();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_account_insert_iban` BEFORE INSERT ON `Account` FOR EACH ROW BEGIN
    SET NEW.iban = generate_unique_iban();
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_customer_total_money` AFTER UPDATE ON `Account` FOR EACH ROW BEGIN
    DECLARE total_money_change INT;
    SET total_money_change = NEW.money - OLD.money;
    UPDATE Customer
    SET total_money = total_money + total_money_change
    WHERE cust_id = NEW.cust_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Customer`
--

CREATE TABLE `Customer` (
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `total_money` int(11) NOT NULL DEFAULT 0,
  `active_loan` tinyint(1) NOT NULL,
  `spendings` int(11) NOT NULL,
  `total_income` int(11) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Customer`
--

INSERT INTO `Customer` (`name`, `last_name`, `cust_id`, `total_money`, `active_loan`, `spendings`, `total_income`) VALUES
('John', 'Doe', 1, 5000, 0, 0, 111948),
('Alice', 'Smith', 2, 7000, 1, 0, 2340);

-- --------------------------------------------------------

--
-- Table structure for table `loan_history`
--

CREATE TABLE `loan_history` (
  `acc_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `left_to_pay_off` int(11) NOT NULL,
  `total_amount_of_payments` int(11) NOT NULL,
  `payments_left` int(11) NOT NULL,
  `begin_date` date NOT NULL,
  `end_date` date NOT NULL,
  `payment_value` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_history`
--

INSERT INTO `loan_history` (`acc_id`, `amount`, `left_to_pay_off`, `total_amount_of_payments`, `payments_left`, `begin_date`, `end_date`, `payment_value`, `is_active`) VALUES
(3, 10000, 0, 12, 12, '2024-05-15', '2025-05-15', 833, 0),
(4, 15000, 18600, 24, 24, '2024-05-15', '2026-05-15', 625, 1);

--
-- Triggers `loan_history`
--
DELIMITER $$
CREATE TRIGGER `check_and_update_active_loan` BEFORE UPDATE ON `loan_history` FOR EACH ROW BEGIN
    DECLARE active_loans_count INT;
    DECLARE cust_id_var INT;
    
    -- Get the cust_id associated with the account
    SELECT cust_id INTO cust_id_var FROM Account WHERE acc_id = NEW.acc_id;
    
    -- Set is_active to 0 for the modified loan if left_to_pay_off becomes 0
    IF NEW.left_to_pay_off = 0 THEN
        SET NEW.is_active = 0;
    END IF;
    
    -- Count the number of active loans for the customer
    SELECT COUNT(*) INTO active_loans_count 
    FROM loan_history 
    WHERE acc_id IN (SELECT acc_id FROM Account WHERE cust_id = cust_id_var) AND is_active = 1;
    
    -- If there are no other active loans, set active_loan to false for the customer
    IF active_loans_count = 0 THEN
        UPDATE Customer
        SET active_loan = 0
        WHERE cust_id = cust_id_var;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_active_loan` AFTER INSERT ON `loan_history` FOR EACH ROW BEGIN
    DECLARE cust_id_var INT;
    SELECT cust_id INTO cust_id_var FROM Account WHERE acc_id = NEW.acc_id;
    UPDATE Customer
    SET active_loan = 1
    WHERE cust_id = cust_id_var;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_loan_defaults` BEFORE INSERT ON `loan_history` FOR EACH ROW BEGIN
    DECLARE payments_left_var INT;
    DECLARE payment_value_var DECIMAL(10, 2);
    SET NEW.begin_date = CURDATE();
    SET NEW.end_date = DATE_ADD(CURDATE(), INTERVAL NEW.total_amount_of_payments MONTH);
    SET NEW.is_active = 1;
    SET NEW.left_to_pay_off = NEW.amount + (NEW.amount * NEW.total_amount_of_payments / 100);
    SET payments_left_var = NEW.total_amount_of_payments;
    SET NEW.payments_left = payments_left_var;
    SET payment_value_var = NEW.amount / NEW.total_amount_of_payments;
    SET NEW.payment_value = payment_value_var;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Workplaces`
--

CREATE TABLE `Workplaces` (
  `cust_id` int(11) NOT NULL,
  `temporary` tinyint(1) NOT NULL,
  `earnings_brutto` int(11) NOT NULL,
  `earnings_netto` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `work_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Workplaces`
--

INSERT INTO `Workplaces` (`cust_id`, `temporary`, `earnings_brutto`, `earnings_netto`, `name`, `work_id`) VALUES
(1, 0, 5000, 3900, 'Software Developer', 21),
(2, 1, 3000, 2340, 'Marketing Manager', 22),
(1, 0, 4000, 3120, 'Accountant', 23),
(1, 0, 134523, 104928, 'pizza guy', 24);

--
-- Triggers `Workplaces`
--
DELIMITER $$
CREATE TRIGGER `calculate_local_netto` BEFORE INSERT ON `Workplaces` FOR EACH ROW BEGIN
    SET NEW.earnings_netto = NEW.earnings_brutto * 0.78;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_netto_payment` AFTER INSERT ON `Workplaces` FOR EACH ROW BEGIN
    DECLARE netto_payment DECIMAL(10, 2);
    SET netto_payment = NEW.earnings_brutto * 0.78; -- Calculate 78% of brutto payment
    UPDATE Customer
    SET total_income = total_income + netto_payment
    WHERE cust_id = NEW.cust_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `decrease_total_income` AFTER DELETE ON `Workplaces` FOR EACH ROW BEGIN
    DECLARE decrease_amount DECIMAL(10, 2);
    SET decrease_amount = OLD.earnings_netto; -- Decrease the total_income by the netto payment of the deleted job
    UPDATE Customer
    SET total_income = total_income - decrease_amount
    WHERE cust_id = OLD.cust_id;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Account`
--
ALTER TABLE `Account`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `Customer`
--
ALTER TABLE `Customer`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `Workplaces`
--
ALTER TABLE `Workplaces`
  ADD PRIMARY KEY (`work_id`),
  ADD KEY `fk_customer_id` (`cust_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Account`
--
ALTER TABLE `Account`
  MODIFY `acc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Workplaces`
--
ALTER TABLE `Workplaces`
  MODIFY `work_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Workplaces`
--
ALTER TABLE `Workplaces`
  ADD CONSTRAINT `fk_customer_id` FOREIGN KEY (`cust_id`) REFERENCES `Customer` (`cust_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
