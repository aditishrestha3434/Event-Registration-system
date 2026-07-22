-- EventHub Database
-- Step 1: Create the database
CREATE DATABASE IF NOT EXISTS eventhub_db;
USE eventhub_db;

-- Step 2: Users table - one row per person who signs up
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    college VARCHAR(100),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 3: Events table - created by an admin (not covered here, so we just seed some rows)
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    venue VARCHAR(100) NOT NULL,
    description TEXT,
    capacity INT NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 4: Registrations table - links a user to an event
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    message TEXT,
    
    status ENUM('Confirmed', 'Pending') DEFAULT 'Confirmed',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY one_registration_per_user_per_event (user_id, event_id)
);

-- Step 5: Seed a few sample events so the site isn't empty
-- INSERT INTO events (event_name, event_date, event_time, venue, description, capacity, image_url) VALUES
-- ('Web Development Workshop', '2026-08-20', '10:00:00', 'Prime College', 'Learn HTML, CSS, JavaScript, PHP and MySQL by building real-world projects. Designed for beginners who want practical experience.', 100, 'https://picsum.photos/400/220?1'),
-- ('AI Seminar', '2026-08-25', '13:00:00', 'Hall A', 'Introduction to Artificial Intelligence, covering the basics of machine learning and real-world applications.', 80, 'https://picsum.photos/400/220?2'),
-- ('Hackathon 2026', '2026-08-30', '09:00:00', 'Innovation Lab', '24-hour coding competition for students to build and pitch a working prototype.', 60, 'https://picsum.photos/400/220?3');
