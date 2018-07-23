

CREATE TABLE IF NOT EXISTS `gm_whitelisted_sms` (
  `whitelisted_sms_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` VARCHAR(16) NOT NULL DEFAULT '',
  `message` TEXT,
  `user_id` INT(11) NOT NULL DEFAULT 0,
  `sub_account_id` INT(11) NOT NULL DEFAULT 0,
  `date_time` DATETIME NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`whitelisted_sms_id`),
  KEY `user_id` (`user_id`),
  KEY `sub_account_id` (`sub_account_id`),
  KEY `user_id_2` (`user_id`,`sub_account_id`)
);


CREATE TABLE IF NOT EXISTS `gm_pending_email_data`
(
	id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(id),
	email VARCHAR(84) NOT NULL DEFAULT '',
	INDEX(email),
	code INT(8) NOT NULL DEFAULT 0,
	time INT NOT NULL DEFAULT 0,
	display_name VARCHAR(14) NOT NULL DEFAULT '',
	firstname VARCHAR(64) NOT NULL DEFAULT '',
	lastname VARCHAR(64) NOT NULL DEFAULT '',
	phone VARCHAR(18) NOT NULL DEFAULT '',
	default_sender_id VARCHAR(14) NOT NULL DEFAULT '',
	country_id INT NOT NULL DEFAULT 0,
	default_dial_code INT(10) NOT NULL DEFAULT 0,
	timezone_offset VARCHAR(6) NOT NULL DEFAULT '',
	password VARCHAR(64) NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS `gm_website_configuration`
(
	config_name VARCHAR(64) NOT NULL DEFAULT '',
	UNIQUE(config_name),
	config_value text NOT NULL
);


CREATE TABLE IF NOT EXISTS `gm_contacts`
(
	contact_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(contact_id),
	user_id INT NOT NULL DEFAULT 0,INDEX(user_id),
	sub_account_id INT NOT NULL DEFAULT 0,INDEX(sub_account_id),
	phone VARCHAR(25) NOT NULL DEFAULT '',INDEX(phone),
	firstname VARCHAR(25) NOT NULL DEFAULT '',
	lastname VARCHAR(25) NOT NULL DEFAULT '',
	group_name VARCHAR(84) NOT NULL DEFAULT 'default',INDEX(group_name),
	time INT NOT NULL DEFAULT 0
);


CREATE TABLE IF NOT EXISTS `gm_users`
(
	user_id INT NOT NULL AUTO_INCREMENT,
	email VARCHAR(84) NOT NULL DEFAULT '',INDEX(email),
	firstname VARCHAR(64) NOT NULL DEFAULT '',
	lastname VARCHAR(64) NOT NULL DEFAULT '',
	phone VARCHAR(18) NOT NULL DEFAULT '',
	default_sender_id VARCHAR(14) NOT NULL DEFAULT '',
	country_id INT NOT NULL DEFAULT 0,
	default_dial_code INT(6) NOT NULL DEFAULT 0,
	timezone_offset VARCHAR(6) NOT NULL DEFAULT '',
	password VARCHAR(64) NOT NULL DEFAULT '',INDEX(password),
	balance DOUBLE NOT NULL DEFAULT 0,
	surety_units DOUBLE NOT NULL DEFAULT 0,
	temp_password VARCHAR(64) NOT NULL DEFAULT '',INDEX(temp_password),
	access_level TINYINT NOT NULL DEFAULT 1,
	banned VARCHAR(10) NOT NULL DEFAULT '',
	last_ip VARCHAR(32) NOT NULL DEFAULT '',
	last_seen INT  NOT NULL,
	last_notified DATE  NOT NULL,
	status TINYINT NOT NULL DEFAULT 1,
	credit_notification TINYINT NOT NULL DEFAULT 1,
	verification_file VARCHAR(225) NOT NULL DEFAULT '',
	flag_level TINYINT(1) NOT NULL DEFAULT 0,
	last_surety_updated DATE NOT NULL,
	reseller_account TINYINT(1) NOT NULL DEFAULT 0,
	owing_surety TINYINT(1) NOT NULL DEFAULT 0,
	cache LONGTEXT,
	UNIQUE(email),
	PRIMARY KEY(user_id)
);

CREATE TABLE IF NOT EXISTS `gm_sub_accounts`
(
	sub_account_id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(sub_account_id),
	sub_account VARCHAR(25) NOT NULL DEFAULT '',INDEX(sub_account),
	sub_account_password VARCHAR(25) NOT NULL DEFAULT '',
	user_id INT NOT NULL DEFAULT 0,INDEX(user_id),
	balance INT NOT NULL DEFAULT 0,
	notification_email VARCHAR(225) NOT NULL DEFAULT '',
	default_sender_id VARCHAR(14) NOT NULL DEFAULT '',
	default_dial_code INT(6) NOT NULL DEFAULT 0,
	timezone_offset VARCHAR(6) NOT NULL DEFAULT '',
	last_notified DATE  NOT NULL,
	enabled TINYINT(1) NOT NULL DEFAULT 0,INDEX(enabled),
	owing_surety TINYINT(1) NOT NULL DEFAULT 0
);


CREATE TABLE IF NOT EXISTS `gm_errors`
(
	error_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(error_id),
	user_id INT(11) NOT NULL DEFAULT 0,
	related_id VARCHAR(84) NOT NULL DEFAULT '',
	time INT NOT NULL DEFAULT 0,
	type VARCHAR(32) NOT NULL DEFAULT 'general',
	topic VARCHAR(224) NOT NULL DEFAULT '',
	details TEXT NOT NULL,
	json_details TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS `gm_scheduled_mails`
(
	scheduled_mail_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(scheduled_mail_id),
	time INT NOT NULL DEFAULT 0,
	time_sent INT NOT NULL DEFAULT 0,
	email VARCHAR(224) NOT NULL DEFAULT '',
	sender VARCHAR(224) NOT NULL DEFAULT '',
	sender_name VARCHAR(224) NOT NULL DEFAULT '',
	subject VARCHAR(224) NOT NULL DEFAULT '',
	message LONGTEXT,
	status TINYINT(1) NOT NULL DEFAULT 0,INDEX(status),
	info TEXT NOT NULL
) DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `gm_currencies`;

CREATE TABLE IF NOT EXISTS `gm_currencies`
(
	currency_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(currency_id),
	currency VARCHAR(3) NOT NULL DEFAULT '',UNIQUE(currency),
	iso_code INT(5) NOT NULL DEFAULT 0,
	symbol VARCHAR(8) NOT NULL DEFAULT '',
	currency_title VARCHAR(225) NOT NULL DEFAULT '',
	enabled TINYINT(1) NOT NULL DEFAULT 1,
	decimal_places TINYINT(1) NOT NULL DEFAULT 2,
	value DOUBLE NOT NULL DEFAULT 0
) CHARACTER SET = utf8;


--
--sender: number between 1 and 16 characters long, or an 11 character alphanumeric string.
-- batch_id: time_rand()
-----

CREATE TABLE IF NOT EXISTS `gm_sms_log`
(
	sms_id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(sms_id),
	sender VARCHAR(14) NOT NULL DEFAULT '',
	recipient VARCHAR(25) NOT NULL DEFAULT '',
	message TEXT NOT NULL,
	sub_account_id INT NOT NULL DEFAULT 0,INDEX(sub_account_id),
	user_id INT NOT NULL DEFAULT 0,INDEX(user_id),
	time_submitted INT NOT NULL DEFAULT 0,
	time_scheduled INT NOT NULL DEFAULT 0,
	time_sent INT NOT NULL DEFAULT 0,
	batch_id VARCHAR(25) NOT NULL DEFAULT '',INDEX(batch_id),
	units TINYINT(4) NOT NULL DEFAULT 0,
	pages TINYINT(3) NOT NULL DEFAULT 0,
	reference VARCHAR(35) NOT NULL DEFAULT '',
	type TINYINT(1) NOT NULL DEFAULT 0,
	unicode TINYINT(1) NOT NULL DEFAULT 0,
	status TINYINT(1) NOT NULL DEFAULT 0,INDEX(status),
	units_confirmed TINYINT(1) NOT NULL DEFAULT 0,
	locked TINYINT(1) NOT NULL DEFAULT 0,
	deleted TINYINT(1) NOT NULL DEFAULT 0,INDEX(deleted),
	gateway VARCHAR(64) NOT NULL DEFAULT '',
	route TINYINT(1) NOT NULL DEFAULT 0,
	info VARCHAR(225) NOT NULL DEFAULT '',
	extra_data VARCHAR(225) NOT NULL DEFAULT ''
) CHARACTER SET = utf8;
	

CREATE TABLE IF NOT EXISTS `gm_transactions`
(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` INT NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `related` int(11) NOT NULL DEFAULT 0,
  `amount` double(10,2) NOT NULL DEFAULT '0.00',
  `type` tinyint(2) NOT NULL DEFAULT 0,
  `currency_code` VARCHAR(3) NOT NULL DEFAULT 'NGN',
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `payment_method` VARCHAR(32) NOT NULL DEFAULT '',
  `transaction_reference` VARCHAR(38) NOT NULL DEFAULT '',INDEX(transaction_reference),
  `checksum` VARCHAR(128) NOT NULL DEFAULT '',
  `sms_units` INT NOT NULL DEFAULT 0,
  `net_amount_ngn` DOUBLE NOT NULL DEFAULT 0,
  UNIQUE(`transaction_reference`),
  `details` VARCHAR(225) NOT NULL DEFAULT '',
  `batch_used` VARCHAR(128) NOT NULL DEFAULT '',
  `json_details` text,
  `json_info` text,
  PRIMARY KEY (`id`),
  KEY `time` (`time`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

DROP TABLE IF EXISTS `gm_banks`;

CREATE TABLE IF NOT EXISTS `gm_banks` (
  `bank_id` int(11) NOT NULL AUTO_INCREMENT,
  `bank` VARCHAR(80) NOT NULL DEFAULT '',
  `bank_long_name` VARCHAR(225) NOT NULL DEFAULT '',
  PRIMARY KEY (`bank_id`)
) ;


INSERT INTO `gm_banks` (`bank_id`, `bank`, `bank_long_name`) VALUES
(1, 'ACCESS', 'Access Bank'),
(2, 'CITI', 'Citi Bank'),
(3, 'DIAMOND', 'Diamond Bank'),
(4, 'ECOBANK', 'EcoBank'),
(5, 'ENTERPRISE', 'Enterprise Bank'),
(6, 'FIDELITY', 'Fidelity Bank'),
(7, 'FIRST', 'First Bank'),
(8, 'FCMB', 'First City Monument Bank'),
(9, 'KEYSTONE', 'Keystone Bank'),
(10, 'GTB', 'Guaranty Trust Bank'),
(11, 'SKYE', 'Skye Bank'),
(12, 'STANBIC IBTC', 'Stanbic IBTC Bank'),
(13, 'STANDARD CHARTERED', 'Standard Chartered Bank'),
(14, 'STERLING', 'Sterling Bank'),
(15, 'UNION', 'Union Bank'),
(16, 'UNITY', 'Unity Bank'),
(17, 'UBA', 'United Bank for Africa'),
(18, 'WEMA', 'Wema Bank'),
(19, 'ZENITH', 'Zenith Bank');



CREATE TABLE IF NOT EXISTS `gm_prices`
(
	price_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(price_id),
	price DOUBLE NOT NULL DEFAULT 0,
	UNIQUE(price),
	min_units INT NOT NULL DEFAULT 0,
	bonus_units INT NOT NULL DEFAULT 0
);


CREATE TABLE IF NOT EXISTS `gm_coverage_list`
(
	coverage_id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY(coverage_id),
	continent VARCHAR(48),
	country VARCHAR(225) NOT NULL DEFAULT '',
	country_code varchar(2),
	network VARCHAR(225) NOT NULL DEFAULT '',
	dial_code  INT(6) NOT NULL DEFAULT 0,
	units DOUBLE NOT NULL DEFAULT 0,
	currency VARCHAR(8)
);


DROP TABLE IF EXISTS `gm_countries`;

CREATE TABLE IF NOT EXISTS `gm_countries` (
`country_id` int(11) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`country_id`),
  `country` VARCHAR(225) NOT NULL DEFAULT '',
  `currency_code` VARCHAR(3) NOT NULL DEFAULT '',
  `country_code` varchar(2),
  UNIQUE(country_code),
  `dial_code` INT(6) NOT NULL  
);

--
-- Dumping data for table `countries`
--

INSERT INTO `gm_countries` (`country_id`, `country`, `currency_code`, `country_code`, `dial_code`) VALUES
(1, 'Algeria', 'DZD', 'DZ', '+213'),
(2, 'Angola', 'AOA', 'AO', '+244'),
(3, 'Benin', 'XOF', 'BJ', '+229'),
(4, 'Botswana', 'BWP', 'BW', '+267'),
(5, 'Burkina Faso', 'XOF', 'BF', '+226'),
(6, 'Burundi', 'BIF', 'BI', '+257'),
(7, 'Cameroon', 'XAF', 'CM', '+237'),
(8, 'Cape Verde', 'CVE', 'CV', '+238'),
(9, 'Central African Republic', 'XAF', 'CF', '+236'),
(10, 'Chad', 'XAF', 'TD', '+235'),
(11, 'Comoros', 'KMF', 'KM', '+269'),
(12, 'Congo, Dem.', 'CDF', 'CD', '+243'),
(13, 'Congo, Rep.', 'XAF', 'CG', '+242'),
(14, 'Djibouti', 'DJF', 'DJ', '+253'),
(15, 'Egypt', 'EGP', 'EG', '+20'),
(16, 'Equatorial Guinea', 'XAF', 'GQ', '+240'),
(17, 'Eritrea', 'ERN', 'ER', '+291'),
(18, 'Ethiopia', 'ETB', 'ET', '+251'),
(19, 'Gabon', 'XAF', 'GA', '+241'),
(20, 'Gambia', 'GMD', 'GM', '+220'),
(21, 'Ghana', 'GHS', 'GH', '+233'),
(22, 'Guinea', 'GNF', 'GN', '+224'),
(23, 'Guinea-Bissau', 'GWP', 'GW', '+245'),
(24, 'Kenya', 'KES', 'KE', '+254'),
(25, 'Lesotho', 'LSL', 'LS', '+266'),
(26, 'Liberia', 'LRD', 'LR', '+231'),
(27, 'Libya', 'LYD', 'LY', '+218'),
(28, 'Madagascar', 'MGF', 'MG', '+261'),
(29, 'Malawi', 'MWK', 'MW', '+265'),
(30, 'Mali', 'XOF', 'ML', '+223'),
(31, 'Mauritania', 'MRO', 'MR', '+222'),
(32, 'Mauritius', 'MUR', 'MU', '+230'),
(33, 'Morocco', 'MAD', 'MA', '+212'),
(34, 'Mozambique', 'MZN', 'MZ', '+258'),
(35, 'Namibia', 'NAD', 'NA', '+264'),
(36, 'Niger', 'XOF', 'NE', '+227'),
(37, 'Nigeria', 'NGN', 'NG', '+234'),
(38, 'Rwanda', 'RWF', 'RW', '+250'),
(39, 'Sao Tome/Principe', 'STD', 'ST', '+239'),
(40, 'Senegal', 'XOF', 'SN', '+221'),
(41, 'Seychelles', 'SCR', 'SC', '+248'),
(42, 'Sierra Leone', 'SLL', 'SL', '+232'),
(43, 'Somalia', 'SOS', 'SO', '+252'),
(44, 'South Africa', 'ZAR', 'ZA', '+27'),
(45, 'Sudan', 'SDG', 'SD', '+249'),
(46, 'Swaziland', 'SZL', 'SZ', '+268'),
(47, 'Tanzania', 'TZS', 'TZ', '+255'),
(48, 'Togo', 'XOF', 'TG', '+228'),
(49, 'Tunisia', 'TND', 'TN', '+216'),
(50, 'Uganda', 'UGX', 'UG', '+256'),
(51, 'Zambia', 'ZMW', 'ZM', '+260'),
(52, 'Zimbabwe', 'ZWD', 'ZW', '+263'),
(53, 'Amundsen-Scott', 'USD', NULL, ''),
(54, 'Bangladesh', 'BDT', 'BD', '+880'),
(55, 'Bhutan', 'BTN', 'BT', '+975'),
(56, 'Brunei', 'BND', 'BN', '+673'),
(57, 'Burma (Myanmar)', 'MMK', 'MM', '+95'),
(58, 'Cambodia', 'KHR', 'KH', '+855'),
(59, 'China', 'CNY', 'CN', '+86'),
(60, 'East Timor', 'USD', 'TL', '+670'),
(61, 'India', 'INR', 'IN', '+91'),
(62, 'Indonesia', 'IDR', 'ID', '+62'),
(63, 'Japan', 'JPY', 'JP', '+81'),
(64, 'Kazakhstan', 'KZT', 'KZ', '+7 7'),
(65, 'Korea (north)', 'KPW', 'KP', '+850'),
(66, 'Korea (south)', 'KRW', 'KR', '+82'),
(67, 'Laos', 'LAK', 'LA', '+856'),
(68, 'Malaysia', 'MYR', 'MY', '+60'),
(69, 'Maldives', 'MVR', 'MV', '+960'),
(70, 'Mongolia', 'MNT', 'MN', '+976'),
(71, 'Nepal', 'NPR', 'NP', '+977'),
(72, 'Philippines', 'PHP', 'PH', '+63'),
(73, 'Russian Federation', 'RUB', 'RU', '+7'),
(74, 'Singapore', 'SGD', 'SG', '+65'),
(75, 'Sri Lanka', 'LKR', 'LK', '+94'),
(76, 'Taiwan', 'TWD', 'TW', '+886'),
(77, 'Thailand', 'THB', 'TH', '+66'),
(78, 'Vietnam', 'VND', 'VN', '+84'),
(79, 'Australia', 'AUD', 'AU', '+61'),
(80, 'Fiji', 'FJD', 'FJ', '+679'),
(81, 'Kiribati', 'AUD', 'KI', '+686'),
(82, 'Micronesia', 'USD', 'FM', '+691'),
(83, 'Nauru', 'AUD', 'NR', '+674'),
(84, 'New Zealand', 'NZD', 'NZ', '+64'),
(85, 'Palau', 'USD', 'PW', '+680'),
(86, 'Papua New Guinea', 'PGK', 'PG', '+675'),
(87, 'Samoa', 'WST', 'WS', '+685'),
(88, 'Tonga', 'TOP', 'TO', '+676'),
(89, 'Tuvalu', 'AUD', 'TV', '+688'),
(90, 'Vanuatu', 'VUV', 'VU', '+678'),
(91, 'Anguilla', 'XCD', 'AI', '+1 264'),
(92, 'Antigua/Barbuda', 'XCD', 'AG', '+1246'),
(93, 'Aruba', 'AWG', 'AW', '+297'),
(94, 'Bahamas', 'BSD', 'BS', '+1 242'),
(95, 'Barbados', 'BBD', 'BB', '+1 246'),
(96, 'Cozumel', 'USD', NULL, ''),
(97, 'Cuba', 'CUP', 'CU', '+53'),
(98, 'Dominica', 'XCD', 'DM', '+1767'),
(99, 'Dominican Republic', 'DOP', 'DO', '+1 849'),
(100, 'Grenada', 'XCD', 'GD', '+1 473'),
(101, 'Guadeloupe', 'EUR', 'GP', '+590'),
(102, 'Haiti', 'HTG', 'HT', '+509'),
(103, 'Jamaica', 'JMD', 'JM', '+1 876'),
(104, 'Martinique', 'EUR', 'MQ', '+596'),
(105, 'Montserrat', 'XCD', 'MS', '+1664'),
(106, 'Netherlands Antilles', 'ANG', 'AN', '+599'),
(107, 'Puerto Rico', 'USD', 'PR', '+1 939'),
(108, 'St. Barts', 'USD', 'BL', '+590'),
(109, 'St. Kitts/Nevis', 'XCD', 'KN', '+1869'),
(110, 'St. Lucia', 'XCD', 'LC', '+1758'),
(111, 'St. Martin/Sint Maarten', 'EUR', 'MF', '+590'),
(112, 'St Vincent/Grenadines', 'XCD', 'VC', '+1784'),
(113, 'San Andres', 'USD', NULL, ''),
(114, 'Trinidad/Tobago', 'TTD', 'TT', '+1868'),
(115, 'Turks/Caicos', 'USD', 'TC', '+1649'),
(116, 'Belize', 'BZD', 'BZ', '+501'),
(117, 'Costa Rica', 'CRC', 'CR', '+506'),
(118, 'El Salvador', 'SVC', 'SV', '+503'),
(119, 'Guatemala', 'QTQ', 'GT', '+502'),
(120, 'Honduras', 'HNL', 'HN', '+504'),
(121, 'Nicaragua', 'NIO', 'NI', '+505'),
(122, 'Panama', 'PAB', 'PA', '+507'),
(123, 'Albania', 'ALL', 'AL', '+355'),
(124, 'Andorra', 'EUR', 'AD', '+376'),
(125, 'Austria', 'EUR', 'AT', '+43'),
(126, 'Belarus', 'BYR', 'BY', '+375'),
(127, 'Belgium', 'EUR', 'BE', '+32'),
(128, 'Bosnia/Herzegovina', 'BAM', 'BA', '+387'),
(129, 'Bulgaria', 'BGN', 'BG', '+359'),
(130, 'Croatia', 'HRK', 'HR', '+385'),
(131, 'Czech Republic', 'CZK', 'CZ', '+420'),
(132, 'Denmark', 'DKK', 'DK', '+45'),
(133, 'Estonia', 'EUR', 'EE', '+372'),
(134, 'Finland', 'EUR', 'FI', '+358'),
(135, 'France', 'EUR', 'FR', '+33'),
(136, 'Georgia', 'GEL', 'GE', '+995'),
(137, 'Germany', 'EUR', 'DE', '+49'),
(138, 'Greece', 'EUR', 'GR', '+30'),
(139, 'Hungary', 'HUF', 'HU', '+36'),
(140, 'Iceland', 'ISK', 'IS', '+354'),
(141, 'Ireland', 'EUR', 'IE', '+353'),
(142, 'Italy', 'EUR', 'IT', '+39'),
(143, 'Latvia', 'LVL', 'LV', '+371'),
(144, 'Liechtenstein', 'CHF', 'LI', '+423'),
(145, 'Lithuania', 'LTL', 'LT', '+370'),
(146, 'Luxembourg', 'EUR', 'LU', '+352'),
(147, 'Macedonia', 'MKD', 'MK', '+389'),
(148, 'Malta', 'EUR', 'MT', '+356'),
(149, 'Moldova', 'MDL', 'MD', '+373'),
(150, 'Monaco', 'EUR', 'MC', '+377'),
(151, 'Netherlands', 'EUR', 'NL', '+31'),
(152, 'Norway', 'NOK', 'NO', '+47'),
(153, 'Poland', 'PLN', 'PL', '+48'),
(154, 'Portugal', 'EUR', 'PT', '+351'),
(155, 'Romania', 'RON', 'RO', '+40'),
(156, 'San Marino', 'EUR', 'SM', '+378'),
(157, 'Serbia/Montenegro (Yugoslavia)', 'YUM', 'RS', '+381'),
(158, 'Slovakia', 'EUR', 'SK', '+421'),
(159, 'Slovenia', 'EUR', 'SI', '+386'),
(160, 'Spain', 'EUR', 'ES', '+34'),
(161, 'Sweden', 'SEK', 'SE', '+46'),
(162, 'Switzerland', 'CHF', 'CH', '+41'),
(163, 'Ukraine', 'UAH', 'UA', '+380'),
(164, 'United Kingdom', 'GBP', 'GB', '+44'),
(165, 'Vatican City', 'EUR', 'VA', '+379'),
(166, 'Arctic Ocean', 'USD', NULL, ''),
(167, 'Atlantic Ocean (North)', 'USD', NULL, ''),
(168, 'Atlantic Ocean (South)', 'USD', NULL, ''),
(169, 'Assorted', 'USD', NULL, ''),
(170, 'Caribbean Sea', 'USD', NULL, ''),
(171, 'Greek Isles', 'USD', NULL, ''),
(172, 'Indian Ocean', 'USD', 'IO', '+246'),
(173, 'Mediterranean Sea', 'USD', NULL, ''),
(174, 'Oceania', 'USD', NULL, ''),
(175, 'Pacific Ocean (North)', 'USD', NULL, ''),
(176, 'Pacific Ocean (South)', 'USD', NULL, ''),
(177, 'Afghanistan', 'AFN', 'AF', '+93'),
(178, 'Armenia', 'AMD', 'AM', '+374'),
(179, 'Azerbaijan', 'AZN', 'AZ', '+994'),
(180, 'Bahrain', 'BHD', 'BH', '+973'),
(181, 'Cyprus', 'EUR', 'CY', '+537'),
(182, 'Iran', 'IRR', 'IR', '+98'),
(183, 'Iraq', 'IQD', 'IQ', '+964'),
(184, 'Israel', 'ILS', 'IL', '+972'),
(185, 'Jordan', 'JOD', 'JO', '+962'),
(186, 'Kuwait', 'KWD', 'KW', '+965'),
(187, 'Kyrgyzstan', 'KGS', 'KG', '+996'),
(188, 'Lebanon', 'LBP', 'LB', '+961'),
(189, 'Oman', 'OMR', 'OM', '+968'),
(190, 'Pakistan', 'PKR', 'PK', '+92'),
(191, 'Qatar', 'QAR', 'QA', '+974'),
(192, 'Saudi Arabia', 'SAR', 'SA', '+966'),
(193, 'Syria', 'SYP', 'SY', '+963'),
(194, 'Tajikistan', 'TJS', 'TJ', '+992'),
(195, 'Turkey', 'TRY', 'TR', '+90'),
(196, 'Turkmenistan', 'TMT', 'TM', '+993'),
(197, 'United Arab Emirates', 'AED', 'AE', '+971'),
(198, 'Uzbekistan', 'UZS', 'UZ', '+998'),
(199, 'Yemen', 'YER', 'YE', '+967'),
(200, 'Bermuda', 'BMD', 'BM', '+1 441'),
(201, 'Canada', 'CAD', 'CA', '+1'),
(202, 'Greenland', 'DKK', 'GL', '+299'),
(203, 'Mexico', 'MXN', 'MX', '+52'),
(204, 'United States', 'USD', 'US', '+1'),
(205, 'Argentina', 'ARS', 'AR', '+54'),
(206, 'Bolivia', 'BOB', 'BO', '+591'),
(207, 'Brazil', 'BRL', 'BR', '+55'),
(208, 'Chile', 'CLP', 'CL', '+56'),
(209, 'Colombia', 'COP', 'CO', '+57'),
(210, 'Ecuador', 'ECS', 'EC', '+593'),
(211, 'Guyana', 'GYD', 'GY', '+595'),
(212, 'Paraguay', 'PYG', 'PY', '+595'),
(213, 'Peru', 'PEN', 'PE', '+51'),
(214, 'Suriname', 'SRD', 'SR', '+597'),
(215, 'Uruguay', 'UYU', 'UY', '+598'),
(216, 'Venezuela', 'VEF', 'VE', '+58'),
(217, 'AmericanSamoa', '', 'AS', '+1 684'),
(218, 'Cayman Islands', '', 'KY', '+ 345'),
(219, 'Christmas Island', '', 'CX', '+61'),
(220, 'Cook Islands', '', 'CK', '+682'),
(221, 'Faroe Islands', '', 'FO', '+298'),
(222, 'French Guiana', '', 'GF', '+594'),
(223, 'French Polynesia', '', 'PF', '+689'),
(224, 'Gibraltar', '', 'GI', '+350'),
(225, 'Guam', '', 'GU', '+1 671'),
(226, 'Marshall Islands', '', 'MH', '+692'),
(227, 'Mayotte', '', 'YT', '+262'),
(228, 'Montenegro', '', 'ME', '+382'),
(229, 'New Caledonia', '', 'NC', '+687'),
(230, 'Niue', '', 'NU', '+683'),
(231, 'Norfolk Island', '', 'NF', '+672'),
(232, 'Northern Mariana Islands', '', 'MP', '+1 670'),
(233, 'Solomon Islands', '', 'SB', '+677'),
(234, 'South Georgia and the South Sandwich Islands', '', 'GS', '+500'),
(235, 'Tokelau', '', 'TK', '+690'),
(236, 'Wallis and Futuna', '', 'WF', '+681'),
(237, 'land Islands', '', 'AX', ''),
(238, 'Antarctica', '', 'AQ', ''),
(239, 'Cocos (Keeling) Islands', '', 'CC', '+61'),
(240, 'Cote d''Ivoire', '', 'CI', '+225'),
(241, 'Falkland Islands (Malvinas)', '', 'FK', '+500'),
(242, 'Guernsey', '', 'GG', '+44'),
(243, 'Hong Kong', '', 'HK', '+852'),
(244, 'Isle of Man', '', 'IM', '+44'),
(245, 'Jersey', '', 'JE', '+44'),
(246, 'Macao', '', 'MO', '+853'),
(247, 'Palestinian Territory, Occupied', '', 'PS', '+970'),
(248, 'Pitcairn', '', 'PN', '+872'),
(249, 'Réunion', '', 'RE', '+262'),
(250, 'Saint Helena, Ascension and Tristan Da Cunha', '', 'SH', '+290'),
(251, 'Saint Pierre and Miquelon', '', 'PM', '+508'),
(252, 'Svalbard and Jan Mayen', '', 'SJ', '+47'),
(253, 'Virgin Islands, British', '', 'VG', '+1 284'),
(254, 'Virgin Islands, U.S.', '', 'VI', '+1 340');