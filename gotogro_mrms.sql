-- Check if the database exists, and create it if not
CREATE DATABASE IF NOT EXISTS gotogro_mrms;

USE gotogro_mrms;

CREATE TABLE IF NOT EXISTS members (
	Member_ID int(5) NOT NULL,
	Full_Name varchar(50) NOT NULL,
	Email_Address varchar(50) NOT NULL,
	Phone_Number varchar(12) NOT NULL,
	DOB varchar(10) NOT NULL,
	Gender varchar(7) NOT NULL,
	Street_Address varchar(50) NOT NULL,
	Country varchar(50) NOT NULL,
	City varchar(50) NOT NULL,
	Postal_Code int(5) NOT NULL
);

INSERT INTO member ( Member_ID, Full_Name, Email_Address, Phone_Number, DOB, Gender, Street_Address, Country, City, Postal_Code)

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