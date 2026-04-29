CREATE TABLE `meal_plan` (
  `id` integer PRIMARY KEY,
  `user_id` int,
  `created_at` timestamp,
  `name` varchar(255)
);

CREATE TABLE `users` (
  `id` integer PRIMARY KEY,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `user_image` varchar(255),
  `email` Varchar(255) UNIQUE,
  `password` Varchar(255),
  `role` enum('User','Admin'),
  `status` enum('Active','Blocked'),
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `meal_plan_recipe` (
  `id` integer PRIMARY KEY,
  `recipe_id` int,
  `meal_plan_id` int,
  `meal_date` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'),
  `meal_time` ENUM('Breakfast','Lunch','Dinner','Snack')
);

CREATE TABLE `recipes` (
  `id` integer PRIMARY KEY,
  `title` varchar(255),
  `description` text,
  `recipe_picture` varchar(255),
  `ingredients` text,
  `instructions` LONGTEXT,
  `prep_time` integer,
  `dietary_type` Enum('Vegeterian','Non-vegeterian','Vegan'),
  `category` Enum('Chicken meals','Vegetables','Desserts','Fish meals'),
  `created_at` timestamp,
  `updated_at` timestamp,
  `difficulty` enum('Easy','Medium','Complicated'),
  `servings` int,
  `author_id` integer
);

ALTER TABLE `recipes` ADD CONSTRAINT `recipes` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

ALTER TABLE `meal_plan_recipe` ADD FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

ALTER TABLE `meal_plan_recipe` ADD FOREIGN KEY (`meal_plan_id`) REFERENCES `meal_plan` (`id`);

ALTER TABLE `meal_plan` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
