-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 29, 2026 at 12:59 PM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `menu_planner`
--
CREATE DATABASE IF NOT EXISTS `menu_planner` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `menu_planner`;

-- --------------------------------------------------------

--
-- Table structure for table `meal_plan`
--

DROP TABLE IF EXISTS `meal_plan`;
CREATE TABLE IF NOT EXISTS `meal_plan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mealplan_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meal_plan_recipe`
--

DROP TABLE IF EXISTS `meal_plan_recipe`;
CREATE TABLE IF NOT EXISTS `meal_plan_recipe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recipe_id` int DEFAULT NULL,
  `meal_plan_id` int DEFAULT NULL,
  `meal_date` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meal_time` enum('Breakfast','Lunch','Dinner','Snack') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mpr_recipe` (`recipe_id`),
  KEY `fk_mpr_mealplan` (`meal_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

DROP TABLE IF EXISTS `recipes`;
CREATE TABLE IF NOT EXISTS `recipes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `recipe_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ingredients` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instructions` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `prep_time` int NOT NULL,
  `dietary_type` enum('Vegeterian','Non-vegeterian','Vegan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('Chicken meals','Vegetables','Desserts','Fish meals') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `difficulty` enum('Easy','Medium','Complicated') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `servings` int NOT NULL,
  `author_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_recipes_author` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `title`, `description`, `recipe_picture`, `ingredients`, `instructions`, `prep_time`, `dietary_type`, `category`, `created_at`, `updated_at`, `difficulty`, `servings`, `author_id`) VALUES
(1, 'Spaghetti Bolognese', 'Classic Italian pasta dish with rich meat sauce.', 'spaghetti.jpg', 'Spaghetti, ground beef, tomato sauce, onion, garlic, olive oil, salt, pepper', 'Cook pasta. Brown beef. Add sauce and simmer. Combine and serve.', 30, 'Vegan', 'Chicken meals', '2026-04-29 12:41:11', '2026-04-29 12:41:52', 'Easy', 4, 1),
(2, 'Vegetable Stir Fry', 'Quick and healthy vegetable stir fry.', 'stirfry.jpg', 'Broccoli, carrots, bell peppers, soy sauce, garlic, ginger, oil', 'Chop veggies. Stir fry in oil. Add sauce and cook 5-7 minutes.', 20, 'Vegan', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:41:11', 'Easy', 2, 2),
(3, 'Chicken Curry', 'Spicy and flavorful chicken curry.', 'chicken_curry.jpg', 'Chicken, curry powder, coconut milk, onion, garlic, spices', 'Cook onions, add chicken and spices. Pour coconut milk and simmer.', 40, 'Non-vegeterian', 'Chicken meals', '2026-04-29 12:41:11', '2026-04-29 12:41:57', 'Medium', 4, 3),
(4, 'Pancakes', 'Fluffy breakfast pancakes.', 'pancakes.jpg', 'Flour, milk, eggs, sugar, baking powder', 'Mix ingredients. Cook batter on skillet until golden.', 15, 'Vegeterian', 'Desserts', '2026-04-29 12:41:11', '2026-04-29 12:56:29', 'Easy', 3, 1),
(5, 'Caesar Salad', 'Fresh salad with creamy dressing.', 'salad.jpg', 'Lettuce, croutons, parmesan, Caesar dressing', 'Toss all ingredients together and serve.', 10, 'Vegeterian', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:56:25', 'Easy', 2, 2),
(6, 'Beef Tacos', 'Mexican-style beef tacos.', 'tacos.jpg', 'Ground beef, taco shells, lettuce, cheese, salsa', 'Cook beef with spices. Fill shells with ingredients.', 25, 'Non-vegeterian', 'Chicken meals', '2026-04-29 12:41:11', '2026-04-29 12:42:20', 'Easy', 4, 3),
(7, 'Chocolate Cake', 'Rich chocolate dessert.', 'cake.jpg', 'Flour, cocoa powder, sugar, eggs, butter', 'Mix ingredients. Bake at 180°C for 30 minutes.', 50, 'Non-vegeterian', 'Desserts', '2026-04-29 12:41:11', '2026-04-29 12:56:19', 'Medium', 6, 1),
(8, 'Grilled Cheese Sandwich', 'Simple and tasty sandwich.', 'sandwich.jpg', 'Bread, cheese, butter', 'Butter bread. Grill with cheese until melted.', 10, 'Non-vegeterian', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:56:16', 'Easy', 1, 2),
(9, 'Lentil Soup', 'Healthy and hearty soup.', 'lentil_soup.jpg', 'Lentils, carrots, celery, onion, spices', 'Boil lentils with vegetables until soft.', 35, 'Vegan', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:41:11', 'Easy', 4, 3),
(10, 'Omelette', 'Quick egg breakfast.', 'omelette.jpg', 'Eggs, salt, pepper, cheese', 'Beat eggs. Cook in pan and fold.', 10, 'Non-vegeterian', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:56:14', 'Easy', 1, 1),
(11, 'BBQ Chicken Wings', 'Crispy wings with BBQ sauce.', 'wings.jpg', 'Chicken wings, BBQ sauce, spices', 'Bake wings and coat with sauce.', 45, 'Non-vegeterian', 'Chicken meals', '2026-04-29 12:41:11', '2026-04-29 12:56:12', 'Medium', 4, 2),
(12, 'Fruit Smoothie', 'Refreshing fruit drink.', 'smoothie.jpg', 'Banana, berries, yogurt, honey', 'Blend all ingredients until smooth.', 5, 'Vegeterian', 'Desserts', '2026-04-29 12:41:11', '2026-04-29 12:56:09', 'Easy', 2, 3),
(13, 'Veggie Burger', 'Plant-based burger.', 'burger.jpg', 'Veggie patty, bun, lettuce, tomato', 'Cook patty. Assemble burger.', 20, 'Vegan', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:41:11', 'Easy', 2, 1),
(14, 'Fried Rice', 'Quick Asian-style rice dish.', 'fried_rice.jpg', 'Rice, eggs, vegetables, soy sauce', 'Stir fry rice with ingredients.', 25, 'Vegeterian', 'Vegetables', '2026-04-29 12:41:11', '2026-04-29 12:56:06', 'Easy', 3, 2),
(15, 'Apple Pie', 'Classic dessert with apples.', 'apple_pie.jpg', 'Apples, flour, sugar, butter, cinnamon', 'Prepare filling and crust. Bake until golden.', 60, 'Vegeterian', 'Desserts', '2026-04-29 12:41:11', '2026-04-29 12:56:01', 'Medium', 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('User','Admin') COLLATE utf8mb4_unicode_ci DEFAULT 'User',
  `status` enum('Active','Blocked') COLLATE utf8mb4_unicode_ci DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `user_image`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ozge', 'Kolay', NULL, 'ozge@mail.com', 'gfdgdffsfsdsdfs456546', 'User', 'Active', '2026-04-29 12:38:19', '2026-04-29 12:38:19'),
(2, 'Chetan', 'K', NULL, 'chetan@mail.com', 'gfdghdfhfd', 'User', 'Active', '2026-04-29 12:38:19', '2026-04-29 12:38:19'),
(3, 'Kair', 'K', '', 'kair@mail.com', 'fhtfhzgzfu788786', 'User', 'Active', '2026-04-29 12:38:51', '2026-04-29 12:38:51');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meal_plan`
--
ALTER TABLE `meal_plan`
  ADD CONSTRAINT `fk_mealplan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `meal_plan_recipe`
--
ALTER TABLE `meal_plan_recipe`
  ADD CONSTRAINT `fk_mpr_mealplan` FOREIGN KEY (`meal_plan_id`) REFERENCES `meal_plan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mpr_recipe` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `fk_recipes_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
