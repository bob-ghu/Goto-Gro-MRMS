-- Check if the database exists, and create it if not
CREATE DATABASE IF NOT EXISTS gotogro_mrms;

USE gotogro_mrms;

CREATE TABLE IF NOT EXISTS members (
	Member_ID INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Full_Name VARCHAR(50) NOT NULL,
	Email_Address VARCHAR(50) NOT NULL,
	Phone_Number VARCHAR(12) NOT NULL,
	DOB VARCHAR(10) NOT NULL,
	Gender VARCHAR(7) NOT NULL,
	Street_Address VARCHAR(50) NOT NULL,
	Country VARCHAR(50) NOT NULL,
    State VARCHAR(50) NOT NULL,
	City VARCHAR(50) NOT NULL,
	Postal_Code INT(5) NOT NULL,
    Status ENUM('active','inactive') DEFAULT 'active'
);

INSERT IGNORE INTO members (Full_Name, Email_Address, Phone_Number, DOB, Gender, Street_Address, Country, State, City, Postal_Code, Status)

VALUES
('James Smith', 'james.smith@example.com', '5551234567', '23/07/1985', 'Male', '123 Maple St', 'USA', 'California', 'Los Angeles', '90001', 'inactive'),
('Sakura Tanaka', 'sakura.tanaka@example.jp', '08012345678', '10/10/1992', 'Female', '456 Sakura Ln', 'Japan', 'Tokyo Prefecture', 'Tokyo', '10012', 'active'),
('Aarav Patel', 'aarav.patel@example.in', '9812345678', '15/04/1995', 'Male', '789 Patel St', 'India', 'Maharashtra', 'Mumbai', '40001', 'active'),
('Marie Dubois', 'marie.dubois@example.fr', '0601234567', '02/02/1983', 'Female', '123 Rue de la Paix', 'France', 'Île-de-France', 'Paris', '75001', 'active'),
('Chen Wei', 'chen.wei@example.cn', '13912345678', '18/08/1998', 'Female', '678 Beijing Rd', 'China', 'Beijing Municipality', 'Beijing', '10001', 'active'),
('David Johnson', 'david.johnson@example.ca', '4161234567', '30/07/1987', 'Male', '789 Queen St', 'Canada', 'Ontario', 'Toronto', '35822', 'active'),
('Kim Min-jun', 'kim.minjun@example.kr', '01012345678', '23/11/1993', 'Male', '456 Gangnam Daero', 'South Korea', 'Seoul Special City', 'Seoul', '60022', 'active'),
('Ravi Kumar', 'ravi.kumar@example.in', '9812345679', '15/09/1989', 'Male', '123 Gandhi St', 'India', 'Delhi', 'New Delhi', '11001', 'active'),
('Emily Anderson', 'emily.anderson@example.com', '5557654321', '05/02/1991', 'Female', '456 Elm St', 'USA', 'Illinois', 'Chicago', '60601', 'active'),
('Nguyen Thi', 'nguyen.thi@example.vn', '0912345678', '14/06/1993', 'Female', '123 Nguyen St', 'Vietnam', 'Ho Chi Minh City', 'Ho Chi Minh City', '70000', 'active'),
('Bjorn Karlsson', 'bjorn.karlsson@example.se', '0701234567', '18/08/1986', 'Male', '789 Stockholm St', 'Sweden', 'Stockholm County', 'Stockholm', '10010', 'active'),
('Mohd Ismail', 'mohd.ismail@example.my', '0123456789', '25/04/1995', 'Male', '123 Jalan Merdeka', 'Malaysia', 'Federal Territory of Kuala Lumpur', 'Kuala Lumpur', '50050', 'active'),
('Sandra Müller', 'sandra.mueller@example.de', '01701234567', '16/05/1987', 'Female', '456 Berlin Str', 'Germany', 'Berlin', 'Berlin', '10115', 'active'),
('Siti Aisyah', 'siti.aisyah@example.bn', '0223456789', '12/12/1998', 'Female', '123 Sultan St', 'Brunei', 'Brunei-Muara', 'Bandar Seri Begawan', '88510', 'active'),
('John Brown', 'john.brown@example.com', '5559876543', '25/12/1979', 'Male', '678 Pine St', 'USA', 'Texas', 'Dallas', '75201', 'active'),
('Aya Yamada', 'aya.yamada@example.jp', '09087654321', '14/02/1992', 'Female', '789 Osaka Ave', 'Japan', 'Osaka Prefecture', 'Osaka', '53001', 'active'),
('Linh Pham', 'linh.pham@example.vn', '0918765432', '11/11/1999', 'Not-say', '678 Hanoi Rd', 'Vietnam', 'Hanoi', 'Hanoi', '10000', 'active'),
('Lisa Svensson', 'lisa.svensson@example.se', '0734567890', '04/05/1991', 'Female', '456 Gothenburg St', 'Sweden', 'Västra Götaland County', 'Gothenburg', '41101', 'active'),
('Hans Schmidt', 'hans.schmidt@example.de', '01787654321', '19/10/1983', 'Male', '789 Munich Str', 'Germany', 'Bavaria', 'Munich', '80331', 'active'),
('Priya Nair', 'priya.nair@example.in', '9890987654', '01/07/1996', 'Female', '123 Marine Dr', 'India', 'Kerala', 'Kochi', '68201', 'active'),
('Alex Li', 'alex.li@example.cn', '13876543210', '22/12/1997', 'Male', '456 Shanghai St', 'China', 'Shanghai Municipality', 'Shanghai', '20001', 'active'),
('Amelia Clarke', 'amelia.clarke@example.com', '5553456789', '08/11/1989', 'Female', '456 Oak St', 'USA', 'California', 'San Francisco', '94101', 'active'),
('Tomoya Sato', 'tomoya.sato@example.jp', '08098765432', '30/06/1994', 'Male', '123 Nagoya Rd', 'Japan', 'Aichi Prefecture', 'Nagoya', '46001', 'active'),
('Faridah Aziz', 'faridah.aziz@example.my', '0117654321', '18/02/1993', 'Female', '789 Kuala St', 'Malaysia', 'Penang', 'George Town', '10200', 'active'),
('Christopher Evans', 'christopher.evans@example.ca', '6041234567', '10/12/1980', 'Not-say', '123 Granville St', 'Canada', 'British Columbia', 'Vancouver', '76522', 'active'),
('Olivia Wilson', 'olivia.wilson@example.com', '5558765432', '25/12/1991', 'Female', '456 Maple St', 'USA', 'Massachusetts', 'Boston', '20101', 'active'),
('Jung Eun Ji', 'jung.eunji@example.kr', '01023456789', '05/10/1997', 'Female', '789 Dongdaemun St', 'South Korea', 'Incheon', 'Incheon', '22001', 'active'),
('Hiroshi Nakamura', 'hiroshi.nakamura@example.jp', '08065432109', '23/09/1985', 'Male', '678 Kobe Rd', 'Japan', 'Hyogo Prefecture', 'Kobe', '65001', 'active'),
('Thanh Vo', 'thanh.vo@example.vn', '0934567891', '18/10/1995', 'Male', '123 Saigon St', 'Vietnam', 'Da Nang', 'Da Nang', '55000', 'active'),
('Maya Muller', 'maya.muller@example.de', '01754321098', '09/09/1989', 'Female', '456 Hamburg Str', 'Germany', 'Hamburg', 'Hamburg', '20095', 'active'),
('Lucas Dupont', 'lucas.dupont@example.fr', '0634567891', '21/05/1988', 'Male', '789 Lyon St', 'France', 'Auvergne-Rhône-Alpes', 'Lyon', '69001', 'active'),
('Nurul Hassan', 'nurul.hassan@example.my', '0145678901', '31/12/1998', 'Male', '123 Georgetown Rd', 'Malaysia', 'Perak', 'Ipoh', '30200', 'active'),
('Hla Tun', 'hla.tun@example.mm', '09987654321', '27/03/1993', 'Female', '456 Yangon Rd', 'Myanmar', 'Yangon Region', 'Yangon', '11000', 'active'),
('Noah Williams', 'noah.williams@example.ca', '4168765432', '13/07/1984', 'Male', '456 Bloor St', 'Canada', 'Ontario', 'Ottawa', '15121', 'active'),
('Sophia Martin', 'sophia.martin@example.fr', '0645678902', '30/05/1993', 'Female', '123 Bordeaux St', 'France', 'Provence-Alpes-Côte Azur', 'Marseille', '13001', 'active'),
('Min Thu', 'min.thu@example.mm', '0945678901', '04/04/1996', 'Male', '789 Mandalay St', 'Myanmar', 'Mandalay Region', 'Mandalay', '50000', 'active'),
('Tobias Andersson', 'tobias.andersson@example.se', '0723456789', '18/05/1989', 'Male', '456 Uppsala St', 'Sweden', 'Uppsala County', 'Uppsala', '75101', 'active'),
('Ahmed Mansoor', 'ahmed.mansoor@example.sg', '91234567', '17/09/1991', 'Not-say', '123 Orchard Rd', 'Singapore', 'Singapore', 'Singapore', '23891', 'active'),
('Eli Cohen', 'eli.cohen@example.il', '0523456789', '30/03/1985', 'Male', '456 Tel Aviv Rd', 'Israel', 'Tel Aviv District', 'Tel Aviv', '51000', 'active'),
('Emma Robinson', 'emma.robinson@example.co.uk', '07912345678', '15/06/1990', 'Female', '678 Manchester Rd', 'United Kingdom', 'England', 'Manchester', '98101', 'active'),
('Fatima El Azzouzi', 'fatima.elazzouzi@example.ma', '0661234567', '11/02/1988', 'Female', '789 Casablanca St', 'Morocco', 'Casablanca-Settat', 'Casablanca', '61234', 'active'),
('Yuki Yamamoto', 'yuki.yamamoto@example.jp', '09023456789', '24/07/1994', 'Male', '123 Fukuoka St', 'Japan', 'Fukuoka Prefecture', 'Fukuoka', '50667', 'active'),
('Anastasia Petrov', 'anastasia.petrov@example.ru', '89012345678', '02/04/1995', 'Female', '456 Moscow Rd', 'Russia', 'Moscow', 'Moscow', '22222', 'active'),
('Elaine Chen', 'elaine.chen@example.sg', '91276543', '14/03/1998', 'Female', '456 Tanjong Pagar', 'Singapore', 'Singapore', 'Singapore', '80530', 'active'),
('Liam Nguyen', 'liam.nguyen@example.vn', '0912345679', '06/06/1996', 'Male', '789 Hai Ba Trung', 'Vietnam', 'Da Nang', 'Da Nang', '53000', 'active'),
('Isabelle Lefevre', 'isabelle.lefevre@example.fr', '0654321098', '30/12/1988', 'Female', '456 Nice St', 'France', 'Île-de-France', 'Paris', '60000', 'active'),
('Alicia Thompson', 'alicia.thompson@example.com', '5553216540', '01/12/1990', 'Female', '789 Cedar St', 'USA', 'Texas', 'Dallas', '80201', 'active'),
('Zaw Win', 'zaw.win@example.mm', '0995432109', '11/12/1997', 'Male', '123 Bago Rd', 'Myanmar', 'Yangon Region', 'Yangon', '80000', 'active'),
('Chloe Chen', 'chloe.chen@example.cn', '13843210987', '18/12/1995', 'Female', '456 Xian Rd', 'China', 'Beijing Municipality', 'Beijing', '71000', 'active'),
('Rachel Stewart', 'rachel.stewart@example.ca', '6047654321', '19/11/1992', 'Female', '789 West St', 'Canada', 'Ontario', 'Toronto', '92217', 'active');

CREATE TABLE IF NOT EXISTS inventory (
    Item_ID INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Quantity INT(5) NOT NULL,
    Retail_Price DECIMAL(10, 2) NOT NULL,
    Selling_Price DECIMAL(10, 2) NOT NULL,
    Supplier VARCHAR(100) NOT NULL,
    Category VARCHAR(50) NOT NULL,
    Brand VARCHAR(50) NOT NULL,
    Reorder_Level INT(5) NOT NULL
);

INSERT IGNORE INTO inventory (Name, Quantity, Retail_Price, Selling_Price, Supplier, Category, Brand, Reorder_Level)

VALUES
-- Fruits and Vegetables
('Bananas', 100, 0.25, 0.30, 'Fresh Farms', 'Fruits and Vegetables', 'FreshCo', 20),
('Apples', 200, 0.50, 0.60, 'Green Growers', 'Fruits and Vegetables', 'GreenThumb', 30),
('Carrots', 150, 0.20, 0.30, 'Veggie Delight', 'Fruits and Vegetables', 'Veggie Fresh', 25),
('Tomatoes', 180, 0.70, 0.85, 'Farm Fresh', 'Fruits and Vegetables', 'Natures Harvest', 40),
('Spinach', 75, 1.00, 1.20, 'Leafy Greens', 'Fruits and Vegetables', 'FreshCo', 15),

-- Dairy
('Milk', 50, 2.00, 2.50, 'Dairy Delight', 'Dairy', 'Pure Dairy', 10),
('Cheddar Cheese', 80, 3.00, 3.50, 'Cheese Co', 'Dairy', 'CheesyGood', 20),
('Yogurt', 120, 1.50, 2.00, 'Happy Cow', 'Dairy', 'Creamy Delight', 30),
('Butter', 60, 2.50, 3.00, 'Butter Farm', 'Dairy', 'Golden Spread', 15),
('Cream', 40, 3.20, 4.00, 'Creamery Co', 'Dairy', 'RichCream', 10),

-- Meat and Poultry
('Chicken Breast', 90, 5.00, 6.00, 'Farm Poultry', 'Meat and Poultry', 'Poultry Fresh', 20),
('Ground Beef', 60, 4.00, 5.00, 'Meat Masters', 'Meat and Poultry', 'Beefy', 15),
('Pork Chops', 45, 4.50, 5.50, 'Meat Lovers', 'Meat and Poultry', 'Pork Delights', 10),
('Turkey Sausage', 70, 3.50, 4.50, 'Turkey Time', 'Meat and Poultry', 'Gobble Good', 25),
('Bacon', 100, 3.00, 3.80, 'Porky Farms', 'Meat and Poultry', 'Crispy Delight', 30),

-- Seafood
('Salmon Fillet', 50, 7.00, 8.50, 'Ocean Fresh', 'Seafood', 'Sea Delights', 10),
('Shrimp', 80, 10.00, 12.00, 'Seafood World', 'Seafood', 'Shrimpy Delight', 25),
('Tuna', 60, 6.00, 7.50, 'Ocean Catch', 'Seafood', 'Fishy Fresh', 15),
('Crab Meat', 30, 15.00, 18.00, 'Crabby Co', 'Seafood', 'Crab Delights', 5),
('Lobster Tail', 20, 20.00, 25.00, 'Luxury Seafood', 'Seafood', 'Lobster Luxury', 3),

-- Beverages
('Orange Juice', 120, 2.50, 3.00, 'Juice Co', 'Beverages', 'Citrus Burst', 30),
('Apple Juice', 150, 2.00, 2.50, 'Fresh Juices', 'Beverages', 'Apple Fresh', 35),
('Cola', 200, 1.00, 1.50, 'Soda Inc.', 'Beverages', 'Fizzy Fun', 50),
('Mineral Water', 250, 0.80, 1.20, 'Water World', 'Beverages', 'Aqua Pure', 40),
('Coffee', 100, 5.00, 6.00, 'Brew Co', 'Beverages', 'Morning Brew', 20),

-- Snacks
('Potato Chips', 180, 1.20, 1.50, 'Snack Factory', 'Snacks', 'Crunchy Crisps', 40),
('Chocolate Bar', 250, 1.00, 1.50, 'ChocoWorld', 'Snacks', 'Sweet Treat', 50),
('Pretzels', 120, 1.50, 2.00, 'Salty Snacks', 'Snacks', 'Salty Twist', 35),
('Popcorn', 140, 1.80, 2.50, 'Popcorn Co', 'Snacks', 'Buttery Pop', 30),
('Granola Bar', 160, 0.80, 1.20, 'Healthy Snacks', 'Snacks', 'Energy Boost', 45),

-- Frozen Foods
('Frozen Pizza', 100, 4.00, 5.00, 'Frozen Foods Co', 'Frozen Foods', 'Pizza Perfection', 25),
('Ice Cream', 80, 3.50, 4.50, 'Cool Treats', 'Frozen Foods', 'Creamy Chill', 20),
('Frozen Vegetables', 120, 2.00, 2.50, 'Frozen Farms', 'Frozen Foods', 'Veggie Delight', 30),
('Frozen Chicken Nuggets', 90, 3.00, 3.80, 'Frozen Foods Co', 'Frozen Foods', 'Chicken Crunch', 20),
('Frozen Fries', 150, 2.50, 3.00, 'Crispy Frozen', 'Frozen Foods', 'Golden Fries', 40);

CREATE TABLE IF NOT EXISTS sales (
    Sales_ID INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Member_ID INT(5) NOT NULL, 
    Item_ID INT(5) NOT NULL,
    Quantity INT(5) NOT NULL,
    Price_per_Unit DECIMAL(10, 2) NOT NULL,
    Total_Price DECIMAL(10, 2) GENERATED ALWAYS AS (Quantity * Price_per_Unit) STORED,
    Sale_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    Payment_Method VARCHAR(50) NOT NULL,
    Staff_ID INT(5) NOT NULL,
    FOREIGN KEY (Member_ID) REFERENCES members(Member_ID),
    FOREIGN KEY (Item_ID) REFERENCES inventory(Item_ID)
);

INSERT INTO sales (Member_ID, Item_ID, Quantity, Price_per_Unit, Sale_Date, Payment_Method, Staff_ID)
VALUES

-- Fruits and Vegetables
(1, 1, 6, 0.30, '2024-10-10 10:15:41', 'Card', 1),  -- Bananas at 0.30 per unit
(2, 2, 5, 0.60, '2024-10-11 11:31:35', 'Cash', 2),         -- Apples at 0.60 per unit
(3, 3, 8, 0.30, '2024-10-12 14:20:54', 'Card', 3),    -- Carrots at 0.30 per unit
(4, 4, 4, 0.85, '2024-10-12 09:46:13', 'Card', 4),   -- Tomatoes at 0.85 per unit
(5, 5, 3, 1.20, '2024-10-13 08:51:23', 'Cash', 5),          -- Spinach at 1.20 per unit

-- Dairy
(6, 6, 2, 2.50, '2024-10-13 17:35:33', 'Card', 1),    -- Milk at 2.50 per unit
(7, 7, 3, 3.50, '2024-10-13 12:40:54', 'Card', 4),  -- Cheddar Cheese at 3.50 per unit
(8, 8, 4, 2.00, '2024-10-14 15:25:41', 'Cash', 3),          -- Yogurt at 2.00 per unit
(9, 9, 1, 3.00, '2024-10-14 18:32:11', 'Card', 2),    -- Butter at 3.00 per unit
(10, 10, 3, 4.00, '2024-10-14 09:10:32', 'Card', 5),-- Cream at 4.00 per unit

-- Meat and Poultry
(11, 11, 5, 6.00, '2024-10-15 13:45:48', 'Cash', 1),       -- Chicken Breast at 6.00 per unit
(12, 12, 4, 5.00, '2024-10-15 16:00:35', 'Card', 5),-- Ground Beef at 5.00 per unit
(13, 13, 3, 5.50, '2024-10-15 10:30:31', 'Card', 4), -- Pork Chops at 5.50 per unit
(14, 14, 7, 4.50, '2024-10-16 08:25:12', 'Cash', 2),       -- Turkey Sausage at 4.50 per unit
(15, 15, 8, 3.80, '2024-10-16 11:50:03', 'Card', 3),-- Bacon at 3.80 per unit

-- Seafood
(16, 16, 2, 8.50, '2024-10-16 14:40:50', 'Card', 5), -- Salmon Fillet at 8.50 per unit
(17, 17, 1, 12.00, '2024-10-17 09:54:03', 'Cash', 3),      -- Shrimp at 12.00 per unit
(18, 18, 5, 7.50, '2024-10-17 12:54:11', 'Card', 4),-- Tuna at 7.50 per unit
(19, 19, 2, 18.00, '2024-10-17 15:16:32', 'Card', 2),-- Crab Meat at 18.00 per unit
(20, 20, 1, 25.00, '2024-10-18 10:33:51', 'Cash', 1);      -- Lobster Tail at 25.00 per unit

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noti TEXT NOT NULL,
    message TEXT NOT NULL,
    notification_type ENUM('info', 'alert', 'warning') NOT NULL,
    is_read BOOLEAN DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    feedback_type ENUM('suggestion', 'complaint', 'compliment') NOT NULL,
    comments TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO feedback (name, email, feedback_type, comments, created_at)
VALUES
('Anonymous', 'anon@gmail.com', 'complaint', 'Low quality toilet paper', '2024-10-15 13:45:48'),
('Old Lady', 'ladyOld@gmail.com', 'compliment', 'Staff are kind and polite', '2024-11-16 12:25:42'),
('Boy', 'boy@gmail.com', 'suggestion', 'Sell more toys', '2024-12-25 14:44:38');

CREATE TABLE IF NOT EXISTS staff (
    Staff_ID INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	Full_Name VARCHAR(50) NOT NULL,
    Username VARCHAR(50) NOT NULL,
	Email_Address VARCHAR(50) NOT NULL,
	Password VARCHAR(30) NOT NULL,
	Role VARCHAR(10) NOT NULL,
    Profile_Picture VARCHAR(100) NOT NULL
);

INSERT INTO staff (Full_Name, Username, Email_Address, Password, Role, Profile_Picture)
VALUES
('Siew Yat Fei', 'Bob', 'siew@gmail.com', 'Password', 'Manager', '../images/Bob.jpg'),
('Goh Mun Hong', 'Hong', 'hong@gmail.com', 'munhong', 'Staff', '../images/Hong.jpg'),
('Chan Chun Xian', 'CCX', 'ccx@gmail.com', 'chunxian', 'Staff', '../images/CCX.jpg'),
('Samuel Ho Shenhao', 'Sam', 'sam@gmail.com', 'hohoho', 'Staff', '../images/Sam.jpg'),
('Guest', 'Guest', 'guest@gmail.com', 'guess', 'Staff', '../images/Guest.jpg');

CREATE TABLE IF NOT EXISTS loginhistory (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Staff_ID INT,
    LoginTime DATETIME DEFAULT CURRENT_TIMESTAMP
);