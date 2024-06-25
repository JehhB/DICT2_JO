CREATE TABLE Personnels (
    personnel_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(128) NOT NULL,
    last_name VARCHAR(128) NOT NULL,
    title VARCHAR(64) NOT NULL
);

CREATE TABLE Projects (
    project_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(128) UNIQUE NOT NULL,
    project_code VARCHAR(64) UNIQUE NOT NULL,
    focal_person_id INTEGER UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY (focal_person_id) REFERENCES Personnels (personnel_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Accounts (
    account_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    personnel_id INTEGER UNSIGNED UNIQUE NOT NULL,
    password_hash VARCHAR(128) NOT NULL,
    current_session VARCHAR(64) NOT NULL,
    CONSTRAINT FOREIGN KEY (personnel_id) REFERENCES Personnels (personnel_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE JobOrder (
    job_order_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id INTEGER UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    scheduled_start_date DATE NOT NULL,
    scheduled_end_date DATE NOT NULL,
    performer_id INTEGER UNSIGNED NOT NULL,
    job_description TEXT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    actual_job_done TEXT NOT NULL,
    remarks TEXT NOT NULL,
    CONSTRAINT FOREIGN KEY (project_id) REFERENCES Projects (project_id) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT FOREIGN KEY (performer_id) REFERENCES Personnels (personnel_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE EndorsedPersonnels(
    endorsed_personnel_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_order_id INTEGER UNSIGNED NOT NULL,
    personnel_id INTEGER UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY (job_order_id) REFERENCES JobOrder (job_order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FOREIGN KEY (personnel_id) REFERENCES Personnels (personnel_id) ON DELETE CASCADE ON UPDATE CASCADE
);

