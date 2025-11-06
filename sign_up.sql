CREATE TABLE user(computing_id VARCHAR(10) NOT NULL, 
                      email VARCHAR(40) NOT NULL,
                      first_name VARCHAR(30), 
                      last_name VARCHAR(50),
                      passwd VARCHAR(255) NOT NULL,
                      PRIMARY KEY (computing_id));