CREATE TABLE users (
    comp_id VARCHAR(10) NOT NULL UNIQUE,
    name VARCHAR(50),
    passwd VARCHAR(25),
    phone_number VARCHAR(15),
    year INT,
    major VARCHAR(50),
    bio VARCHAR(300),
    PRIMARY KEY (comp_id));