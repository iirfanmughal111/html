-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2023 at 11:02 AM
-- Server version: 8.0.35-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `order_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_11_13_073930_create_orders_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `client` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_fulfilled` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `details`, `client`, `is_fulfilled`, `created_at`, `updated_at`) VALUES
(1, 'Eum expedita ipsa quia qui. Molestias dolorum ipsum voluptatem explicabo omnis. Ut velit ad nam ipsa consectetur cumque voluptas. Eveniet et harum sequi voluptatem ut sed quibusdam.', 'Clementine Wiegand', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(2, 'Corporis labore tenetur minus. Qui dolore quo non eum cumque. Ea ducimus inventore sed officia nihil. Doloribus reiciendis quis neque excepturi est recusandae nihil.', 'Miss Audra Hettinger Sr.', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(3, 'Est ratione ducimus ipsa est ipsum. Ut quis dolorum sed suscipit minus a. Temporibus ab officiis cumque voluptas esse quis et. Aspernatur voluptatum mollitia quia.', 'Mr. Reece McKenzie V', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(4, 'Tempora fugiat quia dolores dolores. Et vero facilis et facere. Voluptatem eveniet voluptate autem sint. Id voluptas reiciendis voluptatem soluta aspernatur.', 'Prof. Ralph Buckridge', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(5, 'Corrupti quam id incidunt eaque. Reiciendis atque tempore totam aliquam facere in omnis. Optio omnis quia possimus quod earum inventore enim veniam. Facilis aut possimus minus repellendus.', 'Miss Drew Nader Sr.', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(6, 'Illo dolorum libero architecto cupiditate vero pariatur adipisci. Eum aut rem autem eaque non corporis. Cum labore soluta in autem. Totam nulla libero dolor eum.', 'Sarah Klocko', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(7, 'Expedita voluptatum aut debitis culpa beatae. Et aliquam eveniet non eius laudantium distinctio. Odio consectetur eveniet aut sunt tempore aut reiciendis. Nobis cupiditate illum nesciunt natus laborum aperiam aut voluptatem.', 'Dr. Zechariah Hermann', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(8, 'Rem tempore voluptatem soluta quis maiores. Labore veniam aut voluptate deserunt est laborum. Tempore tempore quia ea consequuntur dicta molestiae sit. Repudiandae totam saepe delectus aliquid architecto fugit voluptas maiores.', 'Rebeka Barton', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(9, 'Quod et dolore dolorem maiores cumque. Esse fuga ipsa ut accusamus in eum quisquam. Numquam aut quia facere consequatur similique velit. Quidem quam quisquam temporibus totam amet molestiae delectus.', 'Grover Schneider', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(10, 'Tenetur saepe quo eos. Qui sit atque quo. Ipsum corporis nihil cum. Ducimus voluptas vel tempora non et minima fugiat.', 'Dr. Deborah Johns', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(11, 'Qui error similique in nesciunt atque deserunt doloribus. Asperiores quos perspiciatis quos in. Distinctio praesentium culpa quis reiciendis. Iusto sit et nulla quam quia.', 'Mr. Malachi Brekke I', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(12, 'Perspiciatis et aliquam harum recusandae nam in fugit. Repudiandae error dolore dolorem eaque id esse. Ab asperiores fugit sunt. Omnis aut dolore dolorum debitis modi.', 'Anna Kuhn', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(13, 'Repudiandae quas nam ut dolor qui. Quae dolor officiis reiciendis ipsum eum nostrum et quia. Quae rerum sed autem qui qui. Aliquid distinctio tenetur ducimus.', 'Flo Anderson', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(14, 'Nemo at assumenda unde sapiente. Ut aliquid odit et officiis eos repudiandae alias. Sunt excepturi nostrum ipsam aut. Nulla voluptatem iusto natus aperiam in.', 'Mr. Federico Goyette', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(15, 'Cumque autem repudiandae et eaque commodi. Ratione illum dolore dolorum qui atque earum earum sit. Necessitatibus nam et sit nisi qui ipsum. Quam exercitationem est atque cupiditate fuga ut.', 'Wanda Gutmann', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(16, 'Consequatur et voluptate et eum est exercitationem. Reiciendis minima minus ea rem labore deserunt omnis. Quia ullam nihil dignissimos quod eum rem qui. Veritatis delectus unde doloremque doloremque minus distinctio.', 'Prof. Fletcher Botsford', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(17, 'Illo doloremque sed quidem dolore. Excepturi dolorem doloremque minima optio quibusdam. Natus consectetur consequuntur suscipit. Quo quisquam est minus reprehenderit architecto.', 'Rogers Bernhard', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(18, 'Qui eaque earum vero dolores voluptatem. Quo magni cum voluptatem sed suscipit doloremque vel repellat. Impedit sit ipsam qui voluptate minus cum. Ullam laborum fuga velit consectetur.', 'Ms. Santina Roberts', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(19, 'Ad dolor voluptas aut dolore quis aliquid. Facere omnis voluptate error quas laudantium. Ut ratione assumenda dolorem sint et est molestiae. Voluptate aut dolor possimus repellat quia omnis.', 'Dr. Lexi Rolfson', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(20, 'Ipsa deleniti fugit odio nemo sed eligendi. Voluptatibus voluptatem voluptas sit ipsum non dolores. Ipsa libero consectetur quia molestiae. Beatae placeat sequi hic quo.', 'Mrs. Karelle Adams DVM', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(21, 'Ad placeat nesciunt illo numquam dolore adipisci. Et et amet ea. Est itaque est dolore. Voluptatibus quasi autem dolor vitae voluptatibus.', 'Mrs. Angeline Hill MD', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(22, 'Nemo veniam quidem qui molestiae rerum et sed provident. Voluptatibus esse consequuntur praesentium adipisci ducimus qui. Commodi eos fuga praesentium hic. Ut id aperiam ullam debitis quis saepe adipisci.', 'Amalia Nikolaus', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(23, 'Laboriosam consectetur dicta velit distinctio. Vitae in adipisci sint magnam sit recusandae esse animi. Aliquam rerum ipsam veniam aspernatur in nam. Est est quaerat tenetur et optio fugit.', 'Chandler Windler', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(24, 'Ipsum aut iusto nisi aut amet. Iste laboriosam rerum sint corrupti laudantium est. Nisi qui id rerum est. Vero vitae quo sit optio dolor ducimus commodi.', 'Miss Katlynn Schoen', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(25, 'Earum error deleniti odit vel. Soluta et sit adipisci quia sed vero. Ipsam deserunt fugiat id aut repudiandae in eius. Qui et nihil fuga perferendis quo dolore.', 'Tressie Kovacek', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(26, 'Dolorum et enim unde. Tenetur totam vel id delectus accusantium inventore et. Incidunt consequatur id quae delectus non voluptatem ullam doloribus. Voluptatem a voluptas adipisci quo.', 'Jarrell Moen', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(27, 'Minus aut quo quos soluta. Eos libero rem eos aut sunt provident. Voluptas neque aliquam nemo hic facilis cum minus. Provident doloribus dolorum modi et culpa.', 'Natasha Turcotte', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(28, 'Porro sint quisquam veritatis ipsam magni. Optio corporis numquam possimus ut veritatis recusandae quas eligendi. Aut aut dolor consequatur iste est. Est consequatur aut dolore inventore.', 'Zola Mertz', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(29, 'Doloremque itaque quis et officia consequatur ipsam. Doloremque odit et incidunt. Ut impedit molestiae repellendus. Voluptates ipsam dignissimos in amet.', 'Axel Langworth', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(30, 'Inventore nemo nesciunt itaque excepturi voluptate ut dolorem. Quas quia id cumque fugiat repudiandae magnam quas ipsum. Cum sit quibusdam aut. Accusantium et iste nihil quia.', 'Effie Rau', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(31, 'Quam rerum distinctio fuga molestiae ipsam dolorum ad. Sunt sit dolor iure maiores doloribus quaerat. Qui qui occaecati expedita modi nisi natus sed. Incidunt ab molestias impedit sit labore et veritatis.', 'Lorena Lebsack', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(32, 'Id neque optio rem incidunt consequatur recusandae aliquid. Sapiente maiores consectetur nesciunt quaerat aliquam neque neque porro. Cupiditate repudiandae dolorem tempore. Neque rem voluptatem quos recusandae ex magni deleniti.', 'Jared Parker', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(33, 'Et et sint et nihil omnis impedit quam. Aliquam aut quo incidunt accusamus maxime voluptatem odit. Soluta cupiditate nihil quia qui sed fuga. Architecto aperiam voluptatem corrupti dolor.', 'Carmel McClure', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(34, 'Aut velit qui deserunt quidem fuga. Quae quasi labore mollitia ut fugiat consequuntur ab. Ut sapiente quia minus itaque. Reiciendis autem error voluptatibus molestias et ea consectetur.', 'Ms. Alexane Wisozk Sr.', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(35, 'Cumque a corrupti earum est fuga et. Rem praesentium optio id maiores ipsum saepe accusamus culpa. Et tempora non minus. Non hic ea consequatur qui saepe quae.', 'Nick Kunze', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(36, 'Hic blanditiis sint aspernatur voluptatem accusantium nemo non error. Nobis dolorem suscipit commodi quia qui est eos dicta. Ullam totam at id nobis. Vero blanditiis accusamus ipsa in.', 'Colin Marks', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(37, 'Libero aliquid dignissimos aut ullam et cum provident. Omnis libero voluptas veniam necessitatibus dolores officiis maxime. Error magni cum et dolorem et unde nostrum. Modi possimus occaecati cum possimus quae quia cumque.', 'Dr. Florence Stehr DVM', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(38, 'Similique consequuntur quis voluptatum sit. Rerum velit beatae voluptatem placeat. A sint rerum soluta cumque assumenda autem. Vel quasi atque ut voluptatem laborum ut dolores.', 'Kitty Weissnat', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(39, 'Sapiente quia doloribus tempore consectetur. Earum iusto doloremque vero. Molestiae aut saepe sed dolor voluptatem qui. Dolores debitis ut odit est quis accusamus porro.', 'Gunner Leffler', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(40, 'Ut odio tenetur asperiores illo molestias. Unde labore ipsam doloremque nihil suscipit vel. Ex inventore voluptatem error neque ratione. Voluptatem provident illo quia repellendus occaecati.', 'Agustina Buckridge', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(41, 'Voluptatem suscipit deserunt aperiam provident dolor sed. Consectetur cumque aliquid cum quo et quae. Earum in esse voluptate et iste ipsum incidunt rerum. Iure necessitatibus molestiae alias similique.', 'Hilario Auer Jr.', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(42, 'Ut itaque est voluptas dolores. Enim amet occaecati aut harum laudantium veritatis libero. Quasi quisquam nisi qui dolorem quisquam. Nihil voluptatem voluptatem sapiente pariatur doloremque facere qui consequuntur.', 'Josefina Bednar Jr.', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(43, 'Labore itaque ea provident. Aut odio non id aut eveniet rerum. Dolorem rerum excepturi consequatur doloribus earum. Fuga amet temporibus dicta aut.', 'Lesly Witting DVM', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(44, 'Necessitatibus qui nobis voluptas inventore autem blanditiis. Explicabo vero earum quaerat ex. Adipisci adipisci ratione animi est sit tempore earum. In ipsum laboriosam atque in ut.', 'Vida Sporer', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(45, 'Nisi expedita ex enim et ut. Iusto dolorum magni quia eum est. Illum quia non delectus eius nam quis et. Minima veritatis possimus et eaque.', 'Prof. Magnolia Wintheiser', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(46, 'Iste id aliquid nemo similique impedit. Esse rem unde rerum debitis. Eaque aspernatur eaque dolores dolor repudiandae explicabo excepturi iusto. Explicabo esse aperiam et fuga aut laudantium asperiores.', 'Manuel Marquardt', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(47, 'Aut dolore excepturi voluptates ducimus. Ipsum ut possimus ad tempore sequi libero et. Perferendis autem dolores et in eum ut aperiam sint. Qui dolor deserunt eos error.', 'Estefania Konopelski', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(48, 'A ullam dolorem voluptatem ea. Repellendus maxime provident eos blanditiis est qui. Ut natus consequatur at aperiam. Illo architecto rem nisi.', 'Kari Kunde', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(49, 'Qui et reiciendis inventore quasi soluta. Quaerat itaque minus vel perferendis omnis ut ea. In ex quia quis repellat et repellendus provident maxime. Voluptatem reiciendis aut fugiat doloribus hic.', 'Bradly Spinka III', 1, '2023-11-13 02:45:08', '2023-11-13 02:45:08'),
(50, 'Sint deleniti aspernatur ut rerum culpa voluptas. Voluptatem sed odit qui a animi. Ratione placeat vel voluptatem ratione veritatis repellat hic. Asperiores error autem corporis qui illo esse.', 'Aleen Sanford', 0, '2023-11-13 02:45:08', '2023-11-13 02:45:08');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
