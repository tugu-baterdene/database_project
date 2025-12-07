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

CREATE TABLE location (
    loc_id INT AUTO_INCREMENT,
    comp_id VARCHAR(10) NOT NULL,
    addr VARCHAR(100) NOT NULL UNIQUE,
    bedroom INT,
    bathroom INT,
    on_off_grounds VARCHAR(15),
    price DECIMAL(10,2),
    extra_cost DECIMAL(10,2),
    PRIMARY KEY (loc_id),
    FOREIGN KEY (comp_id) REFERENCES users(comp_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT check_location_on_off CHECK (on_off_grounds IN ('On','Off') OR on_off_grounds IS NULL)
);
CREATE TABLE apartment (
    addr VARCHAR(100) PRIMARY KEY,
    elevator BOOLEAN,
    num_of_floors INT,
    balcony BOOLEAN,
    pets_allowed BOOLEAN,
    smoking_allowed BOOLEAN,
    FOREIGN KEY (addr) REFERENCES location(addr)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE house (
    addr VARCHAR(100) PRIMARY KEY,
    yard BOOLEAN,
    stories INT,
    porch BOOLEAN,
    FOREIGN KEY (addr) REFERENCES location(addr)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE dorm (
    addr VARCHAR(100) PRIMARY KEY,
    style VARCHAR(50),
    single_double VARCHAR(10),
    kitchen BOOLEAN,
    FOREIGN KEY (addr) REFERENCES location(addr)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
CREATE TABLE landlord (
    l_id VARCHAR(10) PRIMARY KEY,
    name VARCHAR(50),
    contact VARCHAR(50)
);
CREATE TABLE owns (
    l_id VARCHAR(10),
    addr VARCHAR(100),
    PRIMARY KEY (l_id, addr),
    FOREIGN KEY (l_id) REFERENCES landlord(l_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (addr) REFERENCES location(addr)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
CREATE TABLE groups (
    g_id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(20) DEFAULT 'open',
    num_of_people INT,
    addr VARCHAR(100)
);
CREATE TABLE part_of (
    comp_id VARCHAR(10),
    g_id INT,
    PRIMARY KEY (comp_id, g_id),
    FOREIGN KEY (comp_id) REFERENCES users(comp_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (g_id) REFERENCES groups(g_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
