CREATE TABLE IF NOT EXISTS `aa` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'aa',
  `pass` varchar(255) CHARACTER SET latin1 NOT NULL,
  `zx` int(11) NOT NULL,
  PRIMARY KEY (`id`,`name`),
  UNIQUE KEY `pass` (`pass`),
  UNIQUE KEY `namekey` (`name`,`pass`,`zx`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

CREATE TABLE IF NOT EXISTS `asas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) COLLATE latin1_bin NOT NULL,
  `lastname` varchar(255) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=8 ;

CREATE TABLE IF NOT EXISTS `bb` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

CREATE TABLE IF NOT EXISTS `zz` (
  `id` int(11) NOT NULL,
  `name` varchar(13) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT 'lol',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bool` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`,`name`),
  UNIQUE KEY `name` (`name`,`time`),
  KEY `time` (`time`,`bool`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE VIEW `myview` AS SELECT * FROM `zz`;
