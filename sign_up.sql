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

CREATE TABLE preferences (
    comp_id VARCHAR(10) NOT NULL,
    on_off_grounds VARCHAR(15),
    sleeping VARCHAR(50),
    num_of_roommates INT,
    drinking VARCHAR(20),
    smoking VARCHAR(20),
    pets VARCHAR(50),
    budget DECIMAL(10,2),
    CONSTRAINT check_pref_on_off
        CHECK (on_off_grounds IN ('On', 'Off')),
    CONSTRAINT check_drinking
        CHECK (drinking IN ('Yes', 'No')),
    CONSTRAINT check_smoking
        CHECK (smoking IN ('Yes', 'No')),
    CONSTRAINT check_pets
        CHECK (pets IN ('Yes', 'No')),
    PRIMARY KEY (comp_id),
    FOREIGN KEY (comp_id) REFERENCES users(comp_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);