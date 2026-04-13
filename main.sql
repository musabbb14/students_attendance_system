-- USERS TABLE
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
reg_number VARCHAR(50),
name VARCHAR(100),
username VARCHAR(50),
password VARCHAR(100),
role VARCHAR(20),
level INT
);

-- COURSES TABLE (Computer Science Only)
CREATE TABLE courses (
id INT AUTO_INCREMENT PRIMARY KEY,
course_code VARCHAR(20),
course_title VARCHAR(100),
level INT
);

-- SESSIONS TABLE
CREATE TABLE sessions (
id INT AUTO_INCREMENT PRIMARY KEY,
course_id INT,
week VARCHAR(20),
attendance_no INT,
date DATE,
FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- ATTENDANCE TABLE
CREATE TABLE attendance (
id INT AUTO_INCREMENT PRIMARY KEY,
student_id INT,
session_id INT,
FOREIGN KEY (student_id) REFERENCES users(id),
FOREIGN KEY (session_id) REFERENCES sessions(id)
);

-- INSERT COMPUTER SCIENCE COURSES
INSERT INTO courses (course_code, course_title, level) VALUES
('CSC101','Introduction to Computer Science',100),
('CSC102','Programming I',100),
('CSC201','Data Structures',200),
('CSC202','Discrete Mathematics',200),
('CSC301','Operating Systems',300),
('CSC302','Database Systems',300),
('CSC401','Algorithms',400),
('CSC402','Artificial Intelligence',400);

-- INSERT ADMIN USER
INSERT INTO users (reg_number,name,username,password,role,level)
VALUES (NULL,'Admin','admin','admin123','admin',NULL);
