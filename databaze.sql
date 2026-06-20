CREATE DATABASE zoo;
USE zoo;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) 

CREATE TABLE `blog` (
  `id` int(11) NOT NULL,
  `titulli` varchar(120) NOT NULL,
  `autori` varchar(120) NOT NULL,
  `permbajtja` text NOT NULL,
  `imazh` varchar(255) DEFAULT NULL,
  `data_postimit` datetime DEFAULT current_timestamp(),
  `statusi` enum('Publikuar','Draft') DEFAULT 'Publikuar'
)

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `ticket_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `visit_date` date NOT NULL,
  `num_people` int(11) DEFAULT 1,
  `phone` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'cash',
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'confirmed',
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
)

CREATE TABLE `kafshet` (
  `id` int(11) NOT NULL,
  `emri` varchar(100) NOT NULL,
  `lloji` varchar(100) NOT NULL,
  `gjinia` enum('Mashkull','Femër') NOT NULL,
  `mosha` int(11) DEFAULT NULL,
  `vendorigjina` varchar(100) DEFAULT NULL,
  `dieta` varchar(100) DEFAULT NULL,
  `data_ardhjes` date DEFAULT NULL,
  `pershkrimi` text DEFAULT NULL,
  `krijuar_me` datetime DEFAULT current_timestamp(),
  `imazh` varchar(255) DEFAULT NULL
)

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read','replied') DEFAULT 'unread',
  `reply` text DEFAULT NULL
)
CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(10) NOT NULL,
  `feature1` varchar(255) NOT NULL,
  `feature2` varchar(255) NOT NULL,
  `feature3` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) 
