-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS cute_app_db;
USE cute_app_db;

-- สร้างตารางผู้ใช้งาน
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- แนะนำให้เก็บแบบ Hash (เช่น bcrypt)
    display_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- เพิ่มข้อมูลตัวอย่าง (รหัสผ่านคือ 123456)
INSERT INTO users (username, password, display_name) 
VALUES ('cute_user', '123456', 'น้องหมีนำโชค');
