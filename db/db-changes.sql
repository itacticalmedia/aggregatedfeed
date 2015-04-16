ALTER TABLE `feedData` ADD COLUMN `title` VARCHAR(500) NULL AFTER `feedId`, ADD COLUMN `link` VARCHAR(500) NULL AFTER `title`, ADD COLUMN `description` TEXT NULL AFTER `link`; 
ALTER TABLE `feedData`     ADD COLUMN `viewed` TINYINT DEFAULT 0  NULL AFTER `newPosition`, ADD  INDEX `indx_feedid` (`feedId`);

ALTER TABLE `aggregatedfeed`.`feedData` CHANGE `newPosition` `newPosition` DECIMAL(18,9) NULL; 