SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    reg_number VARCHAR(50) DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    level VARCHAR(10) DEFAULT NULL,
    course_id INT(11) DEFAULT NULL, 
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS courses;
CREATE TABLE courses (
    id INT(11) NOT NULL AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL,
    level VARCHAR(10) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions (
    id INT(11) NOT NULL AUTO_INCREMENT,
    course_id INT(11) NOT NULL,
    week VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS attendance;
CREATE TABLE attendance (
    id INT(11) NOT NULL AUTO_INCREMENT,
    student_reg VARCHAR(50) NOT NULL, 
    session_id INT(11) NOT NULL,
    status VARCHAR(20) NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_session FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, username, password, role) VALUES 
('System Admin', 'admin', 'admin123', 'admin'),
('General Student Access', 'student', 'student123', 'student');

INSERT INTO courses (course_code, course_name, level) VALUES 
('COSC101', 'Introduction to Computer Science', '100'),
('COSC102', 'Introduction to Problem Solving with Visual Basic', '100'),

('COSC201', 'Introduction to Information Systems', '200'),
('COSC202', 'Computer Hardware Maintenance', '200'),
('COSC203', 'Computer Logic Design Principles', '200'),
('COSC204', 'System Analysis and Design', '200'),
('COSC205', 'Introduction to Assembly Language', '200'),
('COSC206', 'C Programming Language', '200'),
('COSC207', 'Introduction to Python Programming Language', '200'),
('COSC208', 'Data Structure and Algorithms', '200'),
('COSC210', 'Introduction to Machine Learning', '200'),

('COSC301', 'Object Oriented Programming Fundamentals and C++', '300'),
('COSC303', 'Operating System', '300'),
('COSC305', 'Database Systems I', '300'),
('COSC307', 'Object Oriented Analysis and Design', '300'),
('COSC311', 'Principles of Artificial Intelligence', '300'),
('COSC315', 'Introduction to Java Programming I', '300'),
('COSC319', 'Web Technology', '300'),

('COSC401', 'Software Engineering', '400'),
('COSC403', 'Network Programming', '400'),
('COSC405', 'Data Communication and Networking', '400'),
('COSC407', 'Computer Architecture', '400'),
('COSC409', 'Human Computer Interaction', '400'),
('COSC410', 'Computer Aided Design And Modelling', '400'),
('COSC411', 'Database Systems II', '400'),
('COSC413', 'Simulation Methodology', '400'),
('COSC414', 'Distributed Operating Systems', '400'),
('COSC415', 'Computer Graphics', '400'),
('COSC416', 'Special Topics in Computer Science', '400');

SET FOREIGN_KEY_CHECKS = 1;