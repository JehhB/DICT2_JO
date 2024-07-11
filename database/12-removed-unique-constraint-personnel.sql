ALTER TABLE Accounts DROP FOREIGN KEY Accounts_ibfk_1;
ALTER TABLE Accounts DROP INDEX `personnel_id`; 
ALTER TABLE `Accounts` ADD FOREIGN KEY (`personnel_id`) REFERENCES `Personnels`(`personnel_id`) ON DELETE CASCADE ON UPDATE CASCADE; 
