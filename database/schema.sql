-- Initial Schema for the College Management System (SQLite compatible)

-- Table for all users to handle login and roles
CREATE TABLE IF NOT EXISTS `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` TEXT NOT NULL CHECK(`role` IN ('admin', 'hod', 'faculty', 'student')),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table for departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` VARCHAR(100) UNIQUE NOT NULL,
  `hod_id` INTEGER
  -- Note: The foreign key for hod_id to faculty.id is omitted here to prevent
  -- circular dependency issues in SQLite. This relationship can be managed by the application logic.
);

-- Table for faculty information
CREATE TABLE IF NOT EXISTS `faculty` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER UNIQUE NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `department_id` INTEGER NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
);

-- Table for courses
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `course_code` VARCHAR(20) UNIQUE NOT NULL,
  `course_name` VARCHAR(100) NOT NULL,
  `department_id` INTEGER NOT NULL,
  `credits` INTEGER NOT NULL,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
);

-- Table for student information
CREATE TABLE IF NOT EXISTS `students` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` INTEGER UNIQUE NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `department_id` INTEGER NOT NULL,
  `enrollment_year` INTEGER NOT NULL, -- SQLite uses INTEGER for YEAR
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`department_id`) REFERENCES `departments`(`id`) ON DELETE CASCADE
);

-- Insert a default admin user for initial setup
-- The password is 'admin_password'
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$3J2.F5X7iY9.doG3B2pG/..DSg2v3oVqjY.zaI9SjOFtGz0JpGjS.', 'admin');
