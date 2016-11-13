ALTER TABLE `afup_personnes_physiques`
  ADD `roles` varchar(255) COLLATE 'latin1_general_ci' NOT NULL AFTER `niveau_modules`,
  COMMENT='Personnes physiques';

CREATE TABLE `afup_personnes_morales_invitations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `manager` tinyint(1) unsigned NOT NULL,
  `submitted_on` datetime NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `afup_cotisations`
  CHANGE `type_reglement` `type_reglement` tinyint(3) unsigned NULL DEFAULT '0' AFTER `montant`,
  COMMENT='Cotisation des personnes physiques et morales';

ALTER TABLE `afup_cotisations`
  ADD `token` varchar(255) COLLATE 'latin1_swedish_ci' NULL AFTER `commentaires`;

