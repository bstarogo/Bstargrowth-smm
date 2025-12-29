-- SQL Database for Bstargrowth SMM Panel
-- Schema only

-- Admins table
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  telegram_id BIGINT NOT NULL UNIQUE,
  username VARCHAR(50),
  email VARCHAR(100),
  password VARCHAR(255),
  wallet_balance DECIMAL(20,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Resellers table
CREATE TABLE IF NOT EXISTS resellers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  discount DECIMAL(5,2) DEFAULT 0,
  active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Transactions table
CREATE TABLE IF NOT EXISTS transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  amount DECIMAL(20,2) NOT NULL,
  type ENUM('credit','debit') NOT NULL,
  status ENUM('pending','success','failed') DEFAULT 'pending',
  currency VARCHAR(10) DEFAULT 'NGN',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price_per_unit DECIMAL(20,2) NOT NULL,
  currency VARCHAR(10) DEFAULT 'NGN',
  api_provider VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  link TEXT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(20,2) NOT NULL,
  currency VARCHAR(10) DEFAULT 'NGN',
  status ENUM('pending','processing','completed','failed') DEFAULT 'pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Demo accounts table
CREATE TABLE IF NOT EXISTS demo_accounts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  platform VARCHAR(50),
  link TEXT NOT NULL,
  proof_image TEXT,
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Referrals table
CREATE TABLE IF NOT EXISTS referrals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  referrer_id INT NOT NULL,
  referred_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (referrer_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (referred_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Currencies table
CREATE TABLE IF NOT EXISTS currencies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(10) NOT NULL UNIQUE,
  rate_to_ngn DECIMAL(20,6) NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Telegram settings table
CREATE TABLE IF NOT EXISTS telegram_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bot_token TEXT NOT NULL,
  admin_id BIGINT NOT NULL,
  channel_link TEXT,
  group_link TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert initial admin
INSERT INTO admins (name,email,password)
VALUES ('B Star Admin','bstarogo@gmail.com','Famuyiwa1$');
