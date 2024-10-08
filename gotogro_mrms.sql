-- Check if the database exists, and create it if not
CREATE DATABASE IF NOT EXISTS gotogro_mrms;

-- After creating or confirming the existence of the database, switch to it
USE gotogro_mrms;

-- Now you can create tables or insert records as needed
CREATE TABLE IF NOT EXISTS members (
    Member_ID INT PRIMARY KEY,
    Email_Address VARCHAR(255),
    Phone_Number VARCHAR(20),
    DOB DATE,
    Gender VARCHAR(10),
    Street_Address VARCHAR(255),
    Street_Address2 VARCHAR(255),
    City VARCHAR(100),
    Country VARCHAR(100),
    Region VARCHAR(100),
    Post_Code VARCHAR(20)
);

INSERT INTO member (
    Member_ID, 
    Email_Address, 
    Phone_Number, 
    DOB, 
    Gender, 
    Street_Address, 
    Street_Address2, 
    City, 
    Country, 
    Region, 
    Post_Code
)
VALUES
    (
        ,
        'example1@email.com',
        '1234567890',
        '1990-01-01',
        'Male',
        '123 Main St',
        'Apt 456',
        'New York',
        'USA',
        'New York',
        '10001'
    ),
    (
        2,
        'example2@email.com', 
        '0987654321',
        '1985-06-15',
        'Female',
        '789 Elm St',                -- Street_Address
        NULL,                        -- Street_Address2 (optional)
        'Los Angeles',               -- City
        'USA',                       -- Country
        'California',                -- Region
        '90001'                      -- Post_Code
    ),
    (
        3,                          -- Member_ID
        'example3@email.com',        -- Email_Address
        '5559876543',                -- Phone_Number
        '1977-08-22',                -- DOB
        'Other',                     -- Gender
        '456 Pine St',               -- Street_Address
        'Suite 1A',                  -- Street_Address2
        'Chicago',                   -- City
        'USA',                       -- Country
        'Illinois',                  -- Region
        '60601'                      -- Post_Code
    );