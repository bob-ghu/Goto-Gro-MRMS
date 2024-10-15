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
	City VARCHAR(50) NOT NULL,
	Postal_Code INT(5) NOT NULL
);

INSERT IGNORE INTO members ( Member_ID, Full_Name, Email_Address, Phone_Number, DOB, Gender, Street_Address, Country, City, Postal_Code)

VALUES
(1, 'James Smith', 'james.smith@example.com', '5551234567', '23/07/1985', 'male', '123 Maple St', 'USA', 'Los Angeles', '90001'),
(2, 'Sakura Tanaka', 'sakura.tanaka@example.jp', '08012345678', '10/10/1992', 'female', '456 Sakura Ln', 'Japan', 'Tokyo', '10012'),
(3, 'Aarav Patel', 'aarav.patel@example.in', '9812345678', '15/04/1995', 'male', '789 Patel St', 'India', 'Mumbai', '40001'),
(4, 'Marie Dubois', 'marie.dubois@example.fr', '0601234567', '02/02/1983', 'female', '123 Rue de la Paix', 'France', 'Paris', '75001'),
(5, 'Chen Wei', 'chen.wei@example.cn', '13912345678', '18/08/1998', 'female', '678 Beijing Rd', 'China', 'Beijing', '10001'),
(6, 'David Johnson', 'david.johnson@example.ca', '4161234567', '30/07/1987', 'male', '789 Queen St', 'Canada', 'Toronto', '35822'),
(7, 'Kim Min-jun', 'kim.minjun@example.kr', '01012345678', '23/11/1993', 'male', '456 Gangnam Daero', 'South Korea', 'Seoul', '60022'),
(8, 'Ravi Kumar', 'ravi.kumar@example.in', '9812345679', '15/09/1989', 'male', '123 Gandhi St', 'India', 'Delhi', '11001'),
(9, 'Emily Anderson', 'emily.anderson@example.com', '5557654321', '05/02/1991', 'female', '456 Elm St', 'USA', 'Chicago', '60601'),
(10, 'Nguyen Thi', 'nguyen.thi@example.vn', '0912345678', '14/06/1993', 'female', '123 Nguyen St', 'Vietnam', 'Ho Chi Minh City', '70000'),
(11, 'Bjorn Karlsson', 'bjorn.karlsson@example.se', '0701234567', '18/08/1986', 'male', '789 Stockholm St', 'Sweden', 'Stockholm', '10010'),
(12, 'Mohd Ismail', 'mohd.ismail@example.my', '0123456789', '25/04/1995', 'male', '123 Jalan Merdeka', 'Malaysia', 'Kuala Lumpur', '50050'),
(13, 'Sandra Müller', 'sandra.mueller@example.de', '01701234567', '16/05/1987', 'female', '456 Berlin Str', 'Germany', 'Berlin', '10115'),
(14, 'Siti Aisyah', 'siti.aisyah@example.bn', '0223456789', '12/12/1998', 'female', '123 Sultan St', 'Brunei', 'Bandar Seri Begawan', '88510'),
(15, 'John Brown', 'john.brown@example.com', '5559876543', '25/12/1979', 'male', '678 Pine St', 'USA', 'Dallas', '75201'),
(16, 'Aya Yamada', 'aya.yamada@example.jp', '09087654321', '14/02/1992', 'female', '789 Osaka Ave', 'Japan', 'Osaka', '53001'),
(17, 'Linh Pham', 'linh.pham@example.vn', '0918765432', '11/11/1999', 'not-say', '678 Hanoi Rd', 'Vietnam', 'Hanoi', '10000'),
(18, 'Lisa Svensson', 'lisa.svensson@example.se', '0734567890', '04/05/1991', 'female', '456 Gothenburg St', 'Sweden', 'Gothenburg', '41101'),
(19, 'Hans Schmidt', 'hans.schmidt@example.de', '01787654321', '19/10/1983', 'male', '789 Munich Str', 'Germany', 'Munich', '80331'),
(20, 'Priya Nair', 'priya.nair@example.in', '9890987654', '01/07/1996', 'female', '123 Marine Dr', 'India', 'Kochi', '68201'),
(21, 'Alex Li', 'alex.li@example.cn', '13876543210', '22/12/1997', 'male', '456 Shanghai St', 'China', 'Shanghai', '20001'),
(22, 'Amelia Clarke', 'amelia.clarke@example.com', '5553456789', '08/11/1989', 'female', '456 Oak St', 'USA', 'San Francisco', '94101'),
(23, 'Tomoya Sato', 'tomoya.sato@example.jp', '08098765432', '30/06/1994', 'male', '123 Nagoya Rd', 'Japan', 'Nagoya', '46001'),
(24, 'Faridah Aziz', 'faridah.aziz@example.my', '0117654321', '18/02/1993', 'female', '789 Kuala St', 'Malaysia', 'Penang', '10200'),
(25, 'Christopher Evans', 'christopher.evans@example.ca', '6041234567', '10/12/1980', 'not-say', '123 Granville St', 'Canada', 'Vancouver', '76522'),
(26, 'Olivia Wilson', 'olivia.wilson@example.com', '5558765432', '25/12/1991', 'female', '456 Maple St', 'USA', 'Boston', '20101'),
(27, 'Jung Eun Ji', 'jung.eunji@example.kr', '01023456789', '05/10/1997', 'female', '789 Dongdaemun St', 'South Korea', 'Incheon', '22001'),
(28, 'Hiroshi Nakamura', 'hiroshi.nakamura@example.jp', '08065432109', '23/09/1985', 'male', '678 Kobe Rd', 'Japan', 'Kobe', '65001'),
(29, 'Thanh Vo', 'thanh.vo@example.vn', '0934567891', '18/10/1995', 'male', '123 Saigon St', 'Vietnam', 'Da Nang', '55000'),
(30, 'Maya Muller', 'maya.muller@example.de', '01754321098', '09/09/1989', 'female', '456 Hamburg Str', 'Germany', 'Hamburg', '20095'),
(31, 'Lucas Dupont', 'lucas.dupont@example.fr', '0634567891', '21/05/1988', 'male', '789 Lyon St', 'France', 'Lyon', '69001'),
(32, 'Nurul Hassan', 'nurul.hassan@example.my', '0145678901', '31/12/1998', 'male', '123 Georgetown Rd', 'Malaysia', 'Ipoh', '30200'),
(33, 'Hla Tun', 'hla.tun@example.mm', '09987654321', '27/03/1993', 'female', '456 Yangon Rd', 'Myanmar', 'Yangon', '11000'),
(34, 'Noah Williams', 'noah.williams@example.ca', '4168765432', '13/07/1984', 'male', '456 Bloor St', 'Canada', 'Ottawa', '15121'),
(35, 'Sophia Martin', 'sophia.martin@example.fr', '0645678902', '30/05/1993', 'female', '123 Bordeaux St', 'France', 'Marseille', '13001'),
(36, 'Min Thu', 'min.thu@example.mm', '0945678901', '04/04/1996', 'male', '789 Mandalay St', 'Myanmar', 'Mandalay', '50000'),
(37, 'Tobias Andersson', 'tobias.andersson@example.se', '0723456789', '18/05/1989', 'male', '456 Uppsala St', 'Sweden', 'Uppsala', '75101'),
(38, 'Ahmed Mansoor', 'ahmed.mansoor@example.sg', '91234567', '17/09/1991', 'not-say', '123 Orchard Rd', 'Singapore', 'Singapore', '23891'),
(39, 'Sophie Wong', 'sophie.wong@example.cn', '13987654321', '29/08/1995', 'female', '456 Guangzhou St', 'China', 'Guangzhou', '51000'),
(40, 'Isaac Harris', 'isaac.harris@example.com', '5556543210', '12/06/1982', 'male', '123 Broadway', 'USA', 'Seattle', '98101'),
(41, 'Muhammad Fadzli', 'muhammad.fadzli@example.bn', '0229876543', '25/12/1995', 'male', '789 Brunei Rd', 'Brunei', 'Tutong', '61234'),
(42, 'Oscar Fischer', 'oscar.fischer@example.de', '01765432109', '15/11/1987', 'male', '123 Cologne Str', 'Germany', 'Cologne', '50667'),
(43, 'Freja Nyström', 'freja.nystrom@example.se', '0709876543', '03/04/1992', 'female', '789 Lund St', 'Sweden', 'Lund', '22222'),
(44, 'Elaine Chen', 'elaine.chen@example.sg', '91276543', '14/03/1998', 'female', '456 Tanjong Pagar', 'Singapore', 'Singapore', '80530'),
(45, 'Liam Nguyen', 'liam.nguyen@example.vn', '0912345679', '06/06/1996', 'male', '789 Hai Ba Trung', 'Vietnam', 'Hue', '53000'),
(46, 'Isabelle Lefevre', 'isabelle.lefevre@example.fr', '0654321098', '30/12/1988', 'female', '456 Nice St', 'France', 'Nice', '60000'),
(47, 'Alicia Thompson', 'alicia.thompson@example.com', '5553216540', '01/12/1990', 'female', '789 Cedar St', 'USA', 'Denver', '80201'),
(48, 'Zaw Win', 'zaw.win@example.mm', '0995432109', '11/12/1997', 'male', '123 Bago Rd', 'Myanmar', 'Bago', '80000'),
(49, 'Chloe Chen', 'chloe.chen@example.cn', '13843210987', '18/12/1995', 'female', '456 Xian Rd', 'China', 'Xi An', '71000'),
(50, 'Rachel Stewart', 'rachel.stewart@example.ca', '6047654321', '19/11/1992', 'female', '789 West St', 'Canada', 'Calgary', '92217');

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
(1, 2, 5, 10.99, '2024-09-15 10:15:00', 'Cash', 1),
(2, 4, 2, 3.50, '2024-09-15 11:30:00', 'Card', 2),
(3, 1, 3, 12.75, '2024-09-16 14:20:00', 'Cash', 3),
(4, 5, 1, 25.00, '2024-09-16 09:45:00', 'Card', 1),
(5, 3, 6, 5.20, '2024-09-17 08:50:00', 'Cash', 2),
(2, 6, 4, 15.00, '2024-09-18 17:35:00', 'Card', 4),
(1, 2, 3, 11.00, '2024-09-19 12:40:00', 'Cash', 3),
(3, 5, 2, 23.50, '2024-09-20 15:25:00', 'Card', 5),
(2, 4, 5, 4.99, '2024-09-21 18:15:00', 'Cash', 4),
(4, 3, 7, 6.10, '2024-09-22 09:10:00', 'Card', 1),
(3, 1, 1, 13.50, '2024-09-23 13:45:00', 'Cash', 2),
(1, 6, 4, 17.20, '2024-09-24 16:00:00', 'Card', 3),
(2, 2, 6, 12.99, '2024-09-25 10:30:00', 'Cash', 4),
(5, 3, 3, 4.80, '2024-09-26 08:25:00', 'Card', 5),
(4, 5, 2, 20.75, '2024-09-27 11:50:00', 'Cash', 1),
(1, 1, 5, 14.99, '2024-09-28 14:40:00', 'Card', 2),
(3, 6, 2, 16.10, '2024-09-29 09:00:00', 'Cash', 4),
(4, 4, 6, 7.50, '2024-09-30 12:30:00', 'Card', 3),
(2, 3, 3, 5.25, '2024-10-01 15:15:00', 'Cash', 2),
(5, 2, 2, 10.50, '2024-10-02 10:00:00', 'Card', 5),
(3, 5, 4, 22.30, '2024-10-03 11:45:00', 'Cash', 1),
(1, 6, 3, 19.40, '2024-10-04 14:20:00', 'Card', 4),
(2, 4, 2, 8.50, '2024-10-05 09:35:00', 'Cash', 2),
(4, 3, 7, 6.80, '2024-10-06 10:45:00', 'Card', 1),
(5, 1, 5, 13.99, '2024-10-07 11:30:00', 'Cash', 3),
(2, 5, 6, 21.50, '2024-10-08 15:00:00', 'Card', 5),
(1, 4, 4, 9.99, '2024-10-09 13:55:00', 'Cash', 4),
(3, 2, 3, 11.75, '2024-10-10 14:20:00', 'Card', 2),
(4, 6, 2, 18.00, '2024-10-11 10:15:00', 'Cash', 1),
(5, 3, 1, 5.50, '2024-10-12 09:50:00', 'Card', 3),
(2, 1, 6, 12.10, '2024-10-13 08:25:00', 'Cash', 4),
(1, 5, 2, 24.30, '2024-10-14 11:10:00', 'Card', 5),
(3, 4, 7, 9.50, '2024-10-15 16:45:00', 'Cash', 2),
(4, 2, 3, 10.99, '2024-10-16 14:00:00', 'Card', 1),
(5, 6, 4, 16.75, '2024-10-17 09:35:00', 'Cash', 4),
(1, 3, 5, 6.20, '2024-10-18 13:20:00', 'Card', 3),
(3, 1, 2, 11.50, '2024-10-19 15:00:00', 'Cash', 5),
(4, 5, 3, 23.40, '2024-10-20 10:40:00', 'Card', 1),
(2, 4, 2, 8.90, '2024-10-21 09:25:00', 'Cash', 2),
(5, 6, 6, 18.99, '2024-10-22 12:10:00', 'Card', 4),
(1, 2, 3, 11.10, '2024-10-23 10:00:00', 'Cash', 3),
(3, 3, 1, 5.25, '2024-10-24 11:50:00', 'Card', 5),
(2, 1, 7, 13.99, '2024-10-25 14:10:00', 'Cash', 4),
(4, 5, 4, 22.70, '2024-10-26 13:00:00', 'Card', 2),
(5, 4, 2, 9.75, '2024-10-27 16:30:00', 'Cash', 1);
