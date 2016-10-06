-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.0.17-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------
INSERT INTO `surveys` (`id`, `user_id`, `identifier`, `language`, `name`, `priority`, `status`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
	(1, 1, 'thvVVVXq', 'es', 'Test1', 124, 'open', '2015-07-01 00:00:00', '2016-07-01 00:00:00', '2015-08-28 07:37:00', '2015-08-28 07:54:53');
INSERT INTO `survey_entities` (`id`, `survey_id`, `identifier`, `entity_id`, `entity_type`, `order`) VALUES
	(1, 1, 'GRD2A4DYlwTSLSiO', 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Text', 1000000),
	(2, 1, 'nn4r4s3ghmRjIGS2', 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Text', 2000000),
	(3, 1, 'mcNL3H1uRCSvnBMQ', 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Open', 3000000),
	(4, 1, 'msXz0yxQOrTPuqUJ', 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Open', 4000000),
	(5, 1, 'mpXcquXtUmT613Qw', 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 5000000),
	(6, 1, '3PoMShiwplKUj4ww', 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 6000000),
	(7, 1, 'vlpMnFpJlR1Zo1dU', 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 7000000),
	(8, 1, 'WD7UJ05puQnSk3qE', 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 8000000),
	(9, 1, 'NPoAdFhCDHU016Mx', 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Image', 9000000),
	(10, 1, 'RlCG0gTytJhrqYiG', 3, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Text', 10000000),
	(12, 1, 'KniEl6PbbJDNvcmD', 2, 'App\\TTC\\Models\\Survey\\Entity\\Logic\\Skip', 8500000);
INSERT INTO `survey_entity_l_skip` (`id`, `option_id`, `entity_id`) VALUES
	(2, 14, 10);
INSERT INTO `survey_entity_q_checkbox` (`id`, `question`, `description`, `required`) VALUES
	(1, '5r', '5r', 1),
	(2, '6', '6', 0);
INSERT INTO `survey_entity_q_image` (`id`, `question`, `description`, `required`) VALUES
	(1, '9', '9', 0);
INSERT INTO `survey_entity_q_open` (`id`, `question`, `description`, `required`) VALUES
	(1, '3r', '3r', 1),
	(2, '4', '4', 0);
INSERT INTO `survey_entity_q_radio` (`id`, `question`, `description`, `required`) VALUES
	(1, '7r', '7r', 1),
	(2, '8', '8', 0);
INSERT INTO `survey_entity_q_text` (`id`, `question`, `description`, `required`) VALUES
	(1, '1r', '1r', 1),
	(2, '2', '2', 0),
	(3, 'S1', 'S1', 0);
INSERT INTO `survey_entity_options` (`id`, `entity_id`, `entity_type`, `name`, `value`) VALUES
	(1, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'A'),
	(2, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'B'),
	(3, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'C'),
	(4, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'D'),
	(5, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'A'),
	(6, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'B'),
	(7, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Checkbox', 'option', 'C'),
	(8, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'A'),
	(9, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'B'),
	(10, 1, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'C'),
	(11, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'A'),
	(12, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'B'),
	(13, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'C'),
	(14, 2, 'App\\TTC\\Models\\Survey\\Entity\\Question\\Radio', 'option', 'S1');
INSERT INTO `survey_matchgroups` (`id`, `created_at`, `updated_at`, `survey_id`, `name`) VALUES
	(1, '2015-08-28 07:53:49', '2015-08-28 07:53:49', 1, 'All');
INSERT INTO `survey_matchrules` (`id`, `matchgroup_id`, `attribute`, `operator`, `values`) VALUES
	(1, 1, 'App\\TTC\\MatchMaker\\Attribute\\Age', 'App\\TTC\\MatchMaker\\Operator\\GreaterThan', '["1"]'),
	(2, 1, 'App\\TTC\\MatchMaker\\Attribute\\Country', 'App\\TTC\\MatchMaker\\Operator\\Equals', '[""]'),
	(3, 1, 'App\\TTC\\MatchMaker\\Attribute\\Gender', 'App\\TTC\\MatchMaker\\Operator\\Equals', '[""]');

