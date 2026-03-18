-- data.sql
-- Seed data for Ameziane for Savings and Investments
-- All seeded client passwords are set to 'admin123' (hash copied from default Admin)
USE mini_bank_db;

-- Insert Premium Clients
INSERT IGNORE INTO users (first_name, last_name, email, password_hash, role) VALUES 
('Arthur', 'Pendragon', 'arthur@royal-invest.com', '$2y$10$wI5uFkYgWwGZhFkP9aEq8eVnN7s1u03h0KxgYtF13HhO1.8JkXmGi', 'client'),
('Bruce', 'Wayne', 'bwayne@wayne-enterprises.com', '$2y$10$wI5uFkYgWwGZhFkP9aEq8eVnN7s1u03h0KxgYtF13HhO1.8JkXmGi', 'client'),
('Lara', 'Croft', 'lcroft@croft-holdings.com', '$2y$10$wI5uFkYgWwGZhFkP9aEq8eVnN7s1u03h0KxgYtF13HhO1.8JkXmGi', 'client'),
('Tony', 'Stark', 'tstark@stark-industries.com', '$2y$10$wI5uFkYgWwGZhFkP9aEq8eVnN7s1u03h0KxgYtF13HhO1.8JkXmGi', 'client'),
('Olivia', 'Pope', 'opope@crisis-management.com', '$2y$10$wI5uFkYgWwGZhFkP9aEq8eVnN7s1u03h0KxgYtF13HhO1.8JkXmGi', 'client');

-- Insert Premium Accounts based on User IDs
INSERT IGNORE INTO accounts (user_id, account_number, balance, status) VALUES 
((SELECT id FROM users WHERE email='arthur@royal-invest.com'), 'CL-400000001', 25000000.00, 'active'),
((SELECT id FROM users WHERE email='bwayne@wayne-enterprises.com'), 'CL-400000002', 150000000.50, 'active'),
((SELECT id FROM users WHERE email='lcroft@croft-holdings.com'), 'CL-400000003', 35000000.75, 'active'),
((SELECT id FROM users WHERE email='tstark@stark-industries.com'), 'CL-400000004', 850000000.00, 'active'),
((SELECT id FROM users WHERE email='opope@crisis-management.com'), 'CL-400000005', 15000000.00, 'active');
