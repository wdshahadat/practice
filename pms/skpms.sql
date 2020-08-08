-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 03, 2020 at 11:11 AM
-- Server version: 10.3.23-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `addawahf_pms`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int(11) NOT NULL,
  `companyName` varchar(150) NOT NULL,
  `companyLogo` varchar(75) NOT NULL,
  `userCurrency` varchar(10) NOT NULL,
  `smtpHost` varchar(100) NOT NULL,
  `smtpPort` varchar(10) NOT NULL,
  `smtpAuth` varchar(20) NOT NULL,
  `contactEmail` varchar(120) NOT NULL,
  `emailPassword` varchar(75) NOT NULL,
  `startingDate` varchar(20) NOT NULL,
  `secureAuth` varchar(75) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `companyName`, `companyLogo`, `userCurrency`, `smtpHost`, `smtpPort`, `smtpAuth`, `contactEmail`, `emailPassword`, `startingDate`, `secureAuth`) VALUES
(1, 'Software', 'img/01a8288a39b78633e282748d07bb7514.jpg', 'BDT', 'shahadat-khan.com', '465', 'none', 'shahadat01951251154@gmail.com', 'shahadatkhan54', '07-04-2020', 'e8ef6383ad70992ad5a1ea291907f53e');

-- --------------------------------------------------------

--
-- Table structure for table `fms_admin`
--

CREATE TABLE `fms_admin` (
  `id` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `userName` varchar(100) DEFAULT NULL,
  `password` varchar(75) DEFAULT NULL,
  `userInfo_sc` varchar(1000) DEFAULT NULL,
  `percentage` varchar(11) NOT NULL,
  `photo` varchar(75) NOT NULL,
  `birthday` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `userRoll` varchar(30) NOT NULL,
  `a_date` varchar(20) DEFAULT NULL,
  `u_date` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fms_admin`
--

INSERT INTO `fms_admin` (`id`, `fullName`, `email`, `userName`, `password`, `userInfo_sc`, `percentage`, `photo`, `birthday`, `gender`, `userRoll`, `a_date`, `u_date`) VALUES
(1, 'Shahadat Khan', 'wdshahadat@gmail.com', '0c7540eb7e65b553ec1ba6b20de79608', '$2y$12$hRu3fukxmXfmnHdHsgd6xuUZPCA6AF8OLqUUmSueMdMde1KfOYeJ6', '{\"userName_sc\":\"admin\",\"password_sc\":\"superadmin\",\"oldPassword_sc\":[\"4321\",\"77887848\",\"77887848s\"]}', '80', 'uploadFiles/userPhoto/3f787a05f9e2906a7430f2bb9e3c64a4.jpg', '07-01-2020', 'Male', 'Partner', '07-05-2020', '08-03-2020'),
(4, 'Habaiba Khnom', 'shahadatkhanemail@gmail.com', '36c99ad8b810028f2fa345a6789b5c7b', '$2y$12$bJOueG.5j8OCmQYZhStzqur49lNKwGjgxXS.vSdFuaV44S2V5sQ9u', '{\"userName_sc\":\"habiba\",\"password_sc\":\"habiba\",\"oldPassword_sc\":[]}', '20', 'uploadFiles/userPhoto/2d35c456d13a30d73ce79d36b64c0567.jpg', '07-02-2020', 'Female', 'Partner', '07-05-2020', '08-03-2020'),
(5, 'Md. Saifullah', 'shahadatkhanemail@gmail.com', '5eb8c656c257a28f97309e313831316c', '$2y$12$gZd8TG5hEHY8x/PEnY4Xo.gP8zKNbBNkjL7Gn48FWnPeChdU6nSCe', '{\"userName_sc\":\"saifullah\",\"password_sc\":\"saifullah\",\"oldPassword_sc\":[]}', '', 'uploadFiles/userPhoto/8ed1925f01c4f4f92dd21d48cad01058.jpg', '06-08-2016', 'Male', 'Manager', '08-03-2020', '08-03-2020');

-- --------------------------------------------------------

--
-- Table structure for table `fms_bank`
--

CREATE TABLE `fms_bank` (
  `bankId` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `earnSource` varchar(300) NOT NULL,
  `amount` int(11) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `ba_date` varchar(20) NOT NULL,
  `bu_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fms_bank`
--

INSERT INTO `fms_bank` (`bankId`, `id`, `earnSource`, `amount`, `currency`, `ba_date`, `bu_date`) VALUES
(1, 1, 'Freelancing', 5050, '', '07-05-2020', '');

-- --------------------------------------------------------

--
-- Table structure for table `fms_cost`
--

CREATE TABLE `fms_cost` (
  `cst_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `cst_amount` int(11) NOT NULL,
  `cst_currency` varchar(4) NOT NULL,
  `cost_details` varchar(2000) NOT NULL,
  `cst_a_date` varchar(20) NOT NULL,
  `cst_u_date` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fms_cost`
--

INSERT INTO `fms_cost` (`cst_id`, `id`, `cst_amount`, `cst_currency`, `cost_details`, `cst_a_date`, `cst_u_date`) VALUES
(1, 1, 456, '', '{\"c_productName\":[\"sy\"],\"c_amount\":[\"456\"],\"c_memo\":[\"\"]}', '07-05-2020', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fms_admin`
--
ALTER TABLE `fms_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fms_bank`
--
ALTER TABLE `fms_bank`
  ADD PRIMARY KEY (`bankId`);

--
-- Indexes for table `fms_cost`
--
ALTER TABLE `fms_cost`
  ADD PRIMARY KEY (`cst_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fms_admin`
--
ALTER TABLE `fms_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fms_bank`
--
ALTER TABLE `fms_bank`
  MODIFY `bankId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fms_cost`
--
ALTER TABLE `fms_cost`
  MODIFY `cst_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
