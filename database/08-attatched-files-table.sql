ALTER TABLE `JobOrder`
  DROP `client_position`;

ALTER TABLE
    `JobOrder` ADD `verifier` VARCHAR(32) NOT NULL AFTER `job_order_number`,
    ADD `verifier_position` VARCHAR(32) NOT NULL AFTER `verifier`,
    ADD `status` ENUM(
        'Draft',
        'Pending',
        'Approved',
        'Done',
        'Completed',
        'For Revision'
    ) NOT NULL DEFAULT 'Draft' AFTER `verifier_position`;

CREATE TABLE `AttatchedFiles`(
    `attatched_files_id` INT NOT NULL AUTO_INCREMENT,
    `file_type` VARCHAR(64) NOT NULL,
    `file` LONGBLOB NOT NULL,
	`job_order_id` INTEGER UNSIGNED NOT NULL,
    CONSTRAINT FOREIGN KEY (job_order_id) REFERENCES JobOrder (job_order_id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY(`attatched_files_id`)
) ENGINE = InnoDB;
