-- insert members data 
INSERT IGNORE INTO `members` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
	(1, 'Admin', 'admin@gmail.com', '$2y$10$igZGT2JfIZb0JR2RzbyVJeiHmQ1kTwCkMxpDgVLuK5HzL7l.O2SGu', 'admin','2024-11-01 02:17:36');
	(2, 'member', 'member@gmail.com', '$2y$10$UOq26m/UiI4TkL2PR12KU.PW.00FgP6U/2F/wA8U/Vzny/zpP8PzG', 'member','2024-11-01 02:17:36');

-- insert books data
 INSERT IGNORE INTO `books` (`id`, `title`, `author`, `category`, `isbn`, `quantity`, `added_at`, `photo`) VALUES
	(1, 'The Art of Computer Programming', 'Donald E. Knuth', 'fantasy', '978-0201896831','6','2024-12-27 11:14:12','676e3eac6888a.jpg');

	