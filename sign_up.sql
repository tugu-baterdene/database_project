CREATE TABLE users (
    comp_id VARCHAR(10) NOT NULL UNIQUE,
    stu_name VARCHAR(50),
    phone_number VARCHAR(15),
	passwd VARCHAR(255),
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
        CHECK (on_off_grounds IN ('On', 'Off') OR on_off_grounds IS NULL),
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

-- LANDLORDS TABLE
CREATE TABLE landlords (
   l_id INT NOT NULL AUTO_INCREMENT,
   name VARCHAR(50),
   contact VARCHAR(50),
   PRIMARY KEY (l_id)
);

-- LOCATION TABLE
CREATE TABLE location (
   addr VARCHAR(100) NOT NULL UNIQUE,
   bedroom INT,
   bathroom INT,
   on_off_grounds VARCHAR(20),
   price DECIMAL(10,2),
   extra_cost VARCHAR(100),
   CONSTRAINT check_on_off_grounds
       CHECK (on_off_grounds IN ('On', 'Off') OR on_off_grounds IS NULL),
   CONSTRAINT check_bedroom
       CHECK (bedroom >= 0 AND bedroom < 10),
   CONSTRAINT check_bathroom
       CHECK (bathroom >= 0 AND bathroom < 10),
   PRIMARY KEY (addr)
);


-- GROUPS TABLE
CREATE TABLE groups (
   g_id INT NOT NULL AUTO_INCREMENT,
   status VARCHAR(25),
   num_of_people INT,
   addr VARCHAR(100),
   CONSTRAINT check_status
       CHECK (status IN ('Closed', 'Searching') OR status IS NULL),
   CONSTRAINT check_num_of_people
       CHECK (num_of_people >= 0 AND num_of_people < 20),
   PRIMARY KEY (g_id),
   FOREIGN KEY (addr) REFERENCES location(addr)
       ON DELETE CASCADE
       ON UPDATE CASCADE
);

-- PART_OF TABLE
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

-- APARTMENT TABLE
CREATE TABLE apartment (
   addr VARCHAR(100) NOT NULL UNIQUE,
   elevator BOOLEAN,
   num_of_floors INT,
   balcony BOOLEAN,
   pets BOOLEAN,
   smoking BOOLEAN,
   CONSTRAINT check_num_of_floors
       CHECK (num_of_floors >= 0 AND num_of_floors < 20),
   PRIMARY KEY (addr),
   FOREIGN KEY (addr) REFERENCES location(addr)
       ON DELETE CASCADE
       ON UPDATE CASCADE
);

-- HOUSE TABLE
CREATE TABLE house (
   addr VARCHAR(100) NOT NULL UNIQUE,
   yard BOOLEAN,
   stories INT,
   porch BOOLEAN,
   CONSTRAINT check_stories
       CHECK (stories >= 0 AND stories < 5),
   PRIMARY KEY (addr),
   FOREIGN KEY (addr) REFERENCES location(addr)
       ON DELETE CASCADE
       ON UPDATE CASCADE
);

-- DORM TABLE
CREATE TABLE dorm (
   addr VARCHAR(100) NOT NULL UNIQUE,
   style VARCHAR(50),
   single_double VARCHAR(20),
   kitchen BOOLEAN,
   CONSTRAINT check_style
       CHECK (style IN ('motel', 'hall', 'suite') OR style IS NULL),
   CONSTRAINT check_single_double
       CHECK (single_double IN ('Single', 'Double')),
   PRIMARY KEY (addr),
   FOREIGN KEY (addr) REFERENCES location(addr)
       ON DELETE CASCADE
       ON UPDATE CASCADE
);

-- OWNS TABLE
CREATE TABLE owns (
   l_id INT,
   addr VARCHAR(100),
   PRIMARY KEY (l_id, addr),
   FOREIGN KEY (l_id) REFERENCES landlords(l_id)
       ON DELETE CASCADE
       ON UPDATE CASCADE,
   FOREIGN KEY (addr) REFERENCES location(addr)
       ON DELETE CASCADE
       ON UPDATE CASCADE
);

DELIMITER //

CREATE PROCEDURE search_users_proc(
    IN p_name VARCHAR(50),
    IN p_year INT,
    IN p_major VARCHAR(50),
    IN p_status VARCHAR(20),
    IN p_on_off VARCHAR(15),
    IN p_budget DECIMAL(10,2),
    IN p_drinking VARCHAR(10),
    IN p_smoking VARCHAR(10),
    IN p_pets VARCHAR(10)
)
BEGIN
    SELECT 
        users.*, 
        preferences.budget, 
        preferences.on_off_grounds, 
        location.bedroom, 
        location.bathroom
    FROM users
    LEFT JOIN preferences ON users.comp_id = preferences.comp_id
    LEFT JOIN part_of ON users.comp_id = part_of.comp_id
    LEFT JOIN groups ON part_of.g_id = groups.g_id
    LEFT JOIN location ON groups.addr = location.addr
    LEFT JOIN apartment ON apartment.addr = location.addr
    LEFT JOIN house ON house.addr = location.addr
    LEFT JOIN dorm ON dorm.addr = location.addr
    WHERE (p_name IS NULL OR users.stu_name LIKE CONCAT('%', p_name, '%'))
      AND (p_year IS NULL OR users.school_year = p_year)
      AND (p_major IS NULL OR users.major LIKE CONCAT('%', p_major, '%'))
      AND (p_status IS NULL OR users.status = p_status)
      AND (p_on_off IS NULL OR preferences.on_off_grounds = p_on_off)
      AND (p_budget IS NULL OR preferences.budget <= p_budget)
      AND (p_drinking IS NULL OR preferences.drinking = p_drinking)
      AND (p_smoking IS NULL OR preferences.smoking = p_smoking)
      AND (p_pets IS NULL OR preferences.pets = p_pets);
END //

DELIMITER ;

GRANT SELECT, INSERT, UPDATE, DELETE ON rgp3qv.* TO 'azr7je_a'@'%';

GRANT SELECT, INSERT, UPDATE, DELETE ON rgp3qv.* TO 'yay6mu_a'@'%';

GRANT SELECT, INSERT, UPDATE, DELETE ON rgp3qv.* TO 'fnf8eh_a'@'%';
