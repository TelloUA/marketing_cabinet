CREATE DATABASE IF NOT EXISTS `advertiser_cabinet`;

CREATE TABLE IF NOT EXISTS `advertiser_cabinet`.`users` (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50),
	email VARCHAR(255) NOT NULL,
	password VARCHAR(255) NOT NULL,
	country VARCHAR(50),
	company VARCHAR(50),
	communication_channel VARCHAR(255),
	communication_info VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS `advertiser_cabinet`.`campaigns` (
	id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT(11) NOT NULL,
	name VARCHAR(255) NOT NULL,
	type ENUM('product', 'push') NOT NULL,
	device ENUM('desktop', 'mobile'),
	geo INT(11),
	limit_by_budget INT(11),
	url VARCHAR(255),
	when_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	when_change TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	is_deleted TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS `advertiser_cabinet`.`geo` (
	id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(255) NOT NULL,
	short_name varchar(2) NOT NULL
);

INSERT INTO `advertiser_cabinet`.`geo` (`name`, `short_name`)
VALUES ('Ghana', 'GH'), ('Ivory Coast', 'CI'), ('Nigeria', 'NG'), ('Senegal', 'SN'), ('Zambia', 'ZM'), ('Uganda', 'UG'), ('Burkino Faso', 'BF'), ('Cameroon', 'CM'), ('Kenya', 'KE'), ('Guinea', 'GN'), ('Mozambique', 'MZ'), ('Burundi', 'BI'), ('Congo-Kinshasa', 'CD'), ('Central African Republic', 'CF'), ('Gambia', 'GM'), ('Ethiopia', 'ET'), ('Liberia', 'LR'), ('Madagascar', 'MG'), ('Togo', 'TG'), ('Equatorial Guinea', 'GQ'), ('Benin', 'BJ'), ('Sierra Leone', 'SL'), ('Gabon', 'GA'), ('Congo Brazzaville', 'CG'), ('Chad', 'TD'), ('Mali', 'ML'), ('Guinea-Bissau', 'GW'), ('Tunisia', 'TN'), ('Morocco', 'MA'), ('Egypt', 'EG'), ('United Arab Emirates', 'AE'), ('Saudi Arabia', 'SA'), ('Somalia', 'SO'), ('Iraq', 'IQ'), ('Qatar', 'QA'), ('Sudan', 'SD'), ('Algeria', 'DZ'), ('Mauritania', 'MR'), ('Iran', 'IR'), ('Korea, Republic', 'KR'), ('India', 'IN'), ('Bangladesh', 'BD'), ('Thailand', 'TH'), ('Mongolia', 'MN'), ('Vietnam', 'VN'), ('Nepal', 'NP'), ('Sri Lanka', 'LK'), ('Indonesia', 'ID'), ('Malaysia', 'MY'), ('Myanmar', 'MM'), ('Pakistan', 'PK'), ('Singapore', 'SG'), ('Philippines', 'PH'), ('Cambodia', 'KH'), ('Japan', 'JP'), ('China', 'CN'), ('Hong Kong', 'HK'), ('Taiwan', 'TW'), ('Macao', 'MO'), ('Brazil', 'BR'), ('Mexico', 'MX'), ('Argentina', 'AR'), ('Peru', 'PE'), ('Ecuador', 'EC'), ('Chile', 'CL'), ('Colombia', 'CO'), ('Kazakhstan', 'KZ'), ('Uzbekistan', 'UZ'), ('Azerbaijan', 'AZ'), ('Kyrgyzstan', 'KG'), ('Turkmenistan', 'TM'), ('Tajikistan', 'TJ'), ('Moldova', 'MD'), ('Ukraine', 'UA'), ('Turkey', 'TR');