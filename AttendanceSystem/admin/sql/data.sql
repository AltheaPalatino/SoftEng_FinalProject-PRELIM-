-- users table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','admin') NOT NULL DEFAULT 'student',
  course_id INT DEFAULT NULL,
  year_level VARCHAR(10) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- courses/programs
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL,
  name VARCHAR(255) NOT NULL,
  start_time TIME NOT NULL DEFAULT '08:00:00', -- class start time
  late_grace_minutes INT NOT NULL DEFAULT 10, -- minutes allowed before marked late
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- attendance
CREATE TABLE attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  year_level VARCHAR(10) NOT NULL,
  status ENUM('present','absent') DEFAULT 'present',
  filed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_late TINYINT(1) DEFAULT 0,
  remarks VARCHAR(255) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- excuse letters table
CREATE TABLE excuse_letters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  reason VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) DEFAULT NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);

