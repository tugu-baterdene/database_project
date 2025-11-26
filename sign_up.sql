CREATE TABLE users (
    comp_id VARCHAR(10) NOT NULL UNIQUE,
    stu_name VARCHAR(50),
    phone_number VARCHAR(15),
	passwd VARCHAR(25),
    school_year INT,
    major VARCHAR(50),
    bio VARCHAR(300),
    status VARCHAR(20) DEFAULT 'searching',
    PRIMARY KEY (comp_id));