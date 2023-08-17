CREATE TABLE ministries (
    ministryID INT PRIMARY KEY,
    ministryName VARCHAR(100)
);

ALTER TABLE ministries 
    ADD headOffice INT, 
    ADD CONSTRAINT FOREIGN KEY (headOffice) REFERENCES facilities(facilityID);

CREATE TABLE facilities (
    facilityID INT PRIMARY KEY,
    ministryID INT,
    facilityType VARCHAR(50),
    name VARCHAR(100),
    webAddress VARCHAR(100),
    phoneNumber VARCHAR(50),
    address VARCHAR(255),
    city VARCHAR(100),
    postalCode VARCHAR(50),
    province VARCHAR(50),
    capacity INT,
    inCharge CHAR(10),
    FOREIGN KEY (inCharge) REFERENCES members(medicareNumber),
    FOREIGN KEY (ministryID) REFERENCES ministries(ministryID)
);

ALTER TABLE facilities
    ADD inCharge CHAR(10), 
    ADD CONSTRAINT FOREIGN KEY (inCharge) REFERENCES members(medicareNumber);

CREATE TABLE members (
    medicareNumber CHAR(10) PRIMARY KEY,
    medicareExpiryDate DATE,
    facilityID INT,
    firstName VARCHAR(50),
    lastName VARCHAR(50),
    email VARCHAR(50),
    phoneNumber VARCHAR(50),
    dateOfBirth DATE,
    address VARCHAR(255),
    city VARCHAR(100),
    postalCode VARCHAR(50),
    province VARCHAR(50),
    citizenship VARCHAR(50),
    FOREIGN KEY (facilityID) REFERENCES facilities(facilityID)
);

CREATE TABLE students (
    medicareNumber CHAR(10)  PRIMARY KEY,
    registrationStartDate DATE,
    registrationEndDate DATE,
    level VARCHAR(50),
    FOREIGN KEY (medicareNumber) REFERENCES members(medicareNumber)    
);

CREATE TABLE employees (
    medicareNumber CHAR(10) PRIMARY KEY,
    employeeType VARCHAR(50),
    employmentStartDate DATE,
    employmentEndDate DATE,
    FOREIGN KEY (medicareNumber) REFERENCES members(medicareNumber)
);

CREATE TABLE teachers (
    medicareNumber CHAR(10) PRIMARY KEY,
    FOREIGN KEY (medicareNumber) REFERENCES employees(medicareNumber)
);

CREATE TABLE secondary_teachers (
    medicareNumber CHAR(10) PRIMARY KEY,
    specialty VARCHAR(100),
    counselor BOOL,
    programDirector BOOL,
    administrator BOOL,
    FOREIGN KEY (medicareNumber) REFERENCES teachers(medicareNumber)
);

CREATE TABLE primary_teachers (
    medicareNumber CHAR(10) PRIMARY KEY,
    FOREIGN KEY (medicareNumber) REFERENCES teachers(medicareNumber)
);

CREATE TABLE infections (
    infectionID BIGINT PRIMARY KEY,
    medicareNumber CHAR(10),
    infectionDate DATE,
    infectionNature VARCHAR(255),
    FOREIGN KEY (medicareNumber) REFERENCES members(medicareNumber)
);

CREATE TABLE vaccinations (
    vaccinationID BIGINT PRIMARY KEY,
    medicareNumber CHAR(10),
    date DATE,
    type VARCHAR(50),
    doseNumber INT,
    FOREIGN KEY (medicareNumber) REFERENCES members(medicareNumber)
);

CREATE TABLE educational_facility_types (
    typeID INT PRIMARY KEY,
    name VARCHAR(50)
);

CREATE TABLE educational_facility_to_types (
    educationalFacilityID INT,
    typeID INT,
    PRIMARY KEY (educationalFacilityID, typeID),
FOREIGN KEY (educationalFacilityID) REFERENCES facilities(facilityID),
FOREIGN KEY (typeID) REFERENCES educational_facility_types(typeID)
);



CREATE TABLE employee_histories(
    	medicareNumber CHAR(10),
    	employmentStartDate DATE,
	employmentEndDate DATE,
	facilityID INT,
	FOREIGN KEY (medicareNumber) REFERENCES employees(medicareNumber),
	FOREIGN KEY (facilityID) REFERENCES facilities(facilityID),
	PRIMARY KEY (medicareNumber, employmentStartDate)
)


CREATE TABLE student_histories(
    	medicareNumber CHAR(10),
    	registrationStartDate DATE,
	registrationEndDate DATE,
	facilityID INT,
	FOREIGN KEY (medicareNumber) REFERENCES employees(medicareNumber),
	FOREIGN KEY (facilityID) REFERENCES facilities(facilityID),
	PRIMARY KEY (medicareNumber, registrationStartDate)
)


​​ALTER TABLE members 
ADD CONSTRAINT uk_MemberFacility
UNIQUE (medicareNumber, facilityID)


CREATE TABLE schedule (
scheduleID BIGINT PRIMARY KEY,
medicareNumber CHAR (10),
facilityID INT,
ScheduleDate Date,
StartTime TIME,
EndTime Time,
CONSTRAINT chk_StartBeforeEnd CHECK (StartTime<EndTime),
)


ALTER TABLE schedule 
ADD CONSTRAINT fk_ScheduleMemberFacility
FOREIGN KEY (medicareNumber, facilityID) 
REFERENCES members(medicareNumber, facilityID);


CREATE TABLE emailLog (
logID INT PRIMARY KEY,
facilityName CHAR(20),
emailDate Date,
receiver CHAR (100),
emailSubject VARCHAR(100), 
emailBody CHAR (80),
FOREIGN KEY (receiver) REFERENCES members (medicareNumber),
)

/*Making primary key auto increment*/

ALTER TABLE emailLog 
MODIFY logID INT AUTO_INCREMENT


/*Conflicting Schedule trigger*/

CREATE TRIGGER trg_ConflictingSchedules BEFORE INSERT ON schedule
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM schedule
        WHERE (medicareNumber = NEW.medicareNumber OR facilityID = NEW.facilityID)
        AND scheduleDate = NEW.scheduleDate
        AND (
            (NEW.startTime <= endTime AND NEW.endTime >= startTime)
            OR (startTime <= NEW.endTime AND endTime >= NEW.startTime)
        )
        AND NEW.scheduleID <> scheduleID
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Conflicting schedules';
    END IF;
END;


/*A span of one hour between each schedule on same day for same employee*/

CREATE TRIGGER trg_MinimumDuration BEFORE INSERT ON schedule
FOR EACH ROW
BEGIN
   IF EXISTS (
        SELECT 1 FROM schedule
        WHERE medicareNumber  = NEW.medicareNumber
        AND scheduleDate = NEW.scheduleDate
        AND (
            (TIME_TO_SEC(NEW.startTime) - TIME_TO_SEC(endTime)) < 3600
        )
        AND NEW.scheduleID <> scheduleID
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Minimum duration violation.';
    END IF;
END

/*Check if employee will be vaccinated for 6 months by the time of schedule*/

CREATE TRIGGER trg_CheckVaccination BEFORE INSERT ON Schedule 
FOR EACH ROW 
BEGIN 
	DECLARE lastVaccinationDate DATE;
	
	SELECT v.`date`  INTO lastVaccinationDate
	FROM vaccinations v
	WHERE v.medicareNumber = NEW.medicareNumber;
	
IF lastVaccinationDate IS NULL OR lastVaccinationDate >= DATE_ADD(NEW.scheduleDate, INTERVAL - 6 MONTH) OR lastVaccinationDate > NEW.scheduleDate
	THEN 
	 	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Employee will have not been vaccinated for at least 6 months by the time of schedule date.';
    END IF; 
END


/*System will only allow appointments/schedules 4 weeks ahead of time*/ 

CREATE TRIGGER trg_CheckMaxScheduleDate BEFORE INSERT ON schedule
FOR EACH ROW 
BEGIN 
	IF NEW.scheduleDate > DATE_ADD(CURDATE(), INTERVAL 4 WEEK)
	THEN 
	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Schedule date cannot be more than 4 weeks ahead of the current date and time.';
    END IF; 
END;


/*Teachers can't be scheduled if they have been recently infected ( 2 weeks or less)*/

CREATE TRIGGER trg_CheckInfection BEFORE INSERT ON schedule 
FOR EACH ROW 
BEGIN 
	DECLARE infectdate DATE;
	DECLARE infectionType CHAR(20);
	IF EXISTS (SELECT 1 FROM teachers t WHERE medicareNumber = NEW.medicareNumber) THEN 
	SELECT MAX(i.infectionDate), i.infectionNature INTO infectdate, infectionType
	FROM infections i 
	WHERE i.medicareNumber = NEW.medicareNumber;
	END IF;
	IF infectdate IS NOT NULL AND NEW.scheduleDate <= DATE_ADD(infectdate, INTERVAL 2 WEEK) AND infectionType = 'COVID-19'
	THEN 

	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Teacher is infected within this period. Please reschedule.';
    END IF; 
END;


/*Canceling Schedule of Teacher when they get infected and send email to principal*/


CREATE TRIGGER trg_CancelSchedulesOnUpdate
AFTER UPDATE ON infections FOR EACH ROW 
BEGIN
	DECLARE infectDate DATE;
	DECLARE teacherID CHAR (10);
	DECLARE infectType CHAR (10);
	DECLARE principalEmail VARCHAR(50);
	IF NEW.infectionNature <> OLD.infectionNature THEN
		SET infectDate = NEW.infectionDate;
		SET teacherID = NEW.medicareNumber;
		SET infectType = NEW.infectionNature;
		IF infectType = 'COVID-19'
		THEN
			IF EXISTS (SELECT 1 FROM teachers t WHERE t.medicareNumber = NEW.medicareNumber)
			THEN 
			DELETE FROM schedule s 
			WHERE NEW.medicareNumber= s.medicareNumber 
			AND scheduleDate >=infectDate
			AND scheduleDate < DATE_ADD(infectDate, INTERVAL 2 WEEK);
			
		
			SELECT (select email from members m WHERE m.medicareNumber= f.inCharge) INTO principalEmail
			FROM facilities f 
			WHERE facilityID = (SELECT m.facilityID  FROM members m WHERE m.medicareNumber= teacherID);
			
			INSERT INTO email_log (emailDate, receiver, emailSubject, emailBody, facilityName)
			SELECT infectDate, principalEmail, 'Warning', CONCAT(m.firstName, ' ', m.lastName, ' who teaches in your school has been infected with COVID-19 on ', DATE_FORMAT(infectDate, '%d, %b, %Y'),' ' ,f.name), f.name 
			FROM members m 
			JOIN facilities f ON m.facilityID = f.facilityID
			WHERE m.medicareNumber = teacherID;
			END  IF;
		END IF;
	END IF;
END;


CREATE TRIGGER trg_CancelSchedules AFTER INSERT ON infections
FOR EACH ROW 
BEGIN 
	DECLARE infectDate DATE;
	DECLARE teacherID CHAR (10);
	DECLARE infectType CHAR (10);
	DECLARE principalEmail VARCHAR(50);
	SET infectDate = NEW.infectionDate;
	SET teacherID = NEW.medicareNumber;
	SET infectType = NEW.infectionNature;
	IF infectType = 'COVID-19'
	THEN
		IF EXISTS (SELECT 1 FROM teachers t WHERE t.medicareNumber = NEW.medicareNumber)
		THEN 
		DELETE FROM schedule s 
		WHERE teacherID = s.medicareNumber 
		AND scheduleDate >=infectDate
		AND scheduleDate < DATE_ADD(infectDate, INTERVAL 2 WEEK);
		
	
		SELECT (select email from members m WHERE m.medicareNumber= f.inCharge) INTO principalEmail
		FROM facilities f 
		WHERE facilityID = (SELECT m.facilityID  FROM members m WHERE m.medicareNumber= teacherID);
		
		INSERT INTO email_log (emailDate, receiver, emailSubject, emailBody, facilityName)
		SELECT infectDate, principalEmail, 'Warning', CONCAT(m.firstName, ' ', m.lastName, ' who teaches in your school has been infected with COVID-19 on ', DATE_FORMAT(infectDate, '%d, %b, %Y'), f.name), f.name
		FROM members m 
		JOIN facilities f ON m.facilityID = f.facilityID
		WHERE m.medicareNumber = teacherID;
		END  IF;
	END IF;
END;


/*Procedure to generate email logs*/

CREATE PROCEDURE SendWeeklyScheduleEmails()
BEGIN
	DECLARE done INT DEFAULT FALSE;
	DECLARE firstName VARCHAR(255);
DECLARE lastName VARCHAR(255);
DECLARE email VARCHAR(255);
DECLARE facilityName VARCHAR(255);
DECLARE facilityID INT;
DECLARE schedule TEXT;
DECLARE emailSubject VARCHAR(255);
DECLARE emailBody TEXT;
	DECLARE employeeCursor CURSOR FOR 
		SELECT m.firstName, m.lastName, m.email, f.name, f.facilityID , IFNULL(GROUP_CONCAT(CONCAT(s.scheduleDate, '-', s.startTime, ' to ', s.endTime) ORDER BY s.scheduleDate), 'No Assignment') as 'Schedule'
		FROM members m 
		JOIN employees e on e.medicareNumber = m.medicareNumber 
		JOIN facilities f on m.facilityID = f.facilityID 
		LEFT JOIN schedule s on e.medicareNumber = s.medicareNumber
		AND s.scheduleDate BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 6 DAY)
		GROUP BY m.medicareNumber;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	OPEN employeeCursor;

	read_loop: LOOP 	
		FETCH employeeCursor INTO firstName, lastName, email, facilityName, facilityID, schedule;
		IF done THEN
			LEAVE read_loop;
		END IF;
		
		SET emailSubject = CONCAT(facilityName, ' Schedule for ', DATE_FORMAT (CURDATE() , '%d-%b-%Y'), ' to ',  DATE_FORMAT(DATE_ADD(CURDATE() , INTERVAL 6 DAY), '%d-%b-%Y'));
		SET emailBody = CONCAT ('Facility: ', facilityName, '\n\n', 'Employee: ', firstName, ' ', lastName, '\n\n', 'Schedule for the week:\n\n', schedule);
		
		INSERT INTO emailLog (senderFacilityID, emailDate, receiver, emailSubject, emailBody, facilityName)
		VALUES (facilityID, CURRENT_TIMESTAMP, email, emailSubject, LEFT(emailBody, 80), facilityName);
	
	END LOOP;
	
	CLOSE employeeCursor;
		
END;


/*Automatically creating emails every sunday*/ 

CREATE EVENT CallProcedureEverySunday
ON SCHEDULE EVERY 1 WEEK
STARTS TIMESTAMP(CURRENT_DATE + INTERVAL 1 DAY) + INTERVAL (7 - WEEKDAY(CURRENT_DATE + INTERVAL 1 DAY)) DAY
DO
BEGIN
    CALL SendWeeklyScheduleEmails(); 
END;
