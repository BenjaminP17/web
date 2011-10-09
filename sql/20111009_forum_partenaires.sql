DROP TABLE `afup_forum_partenaires`, `afup_partenaires`;

CREATE  TABLE `afup`.`afup_forum_partenaires` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `id_forum` INT(11) NOT NULL ,
  `id_niveau_partenariat` INT(11) NOT NULL ,
  `ranking` INT(11) NOT NULL ,
  `nom` VARCHAR(100) NOT NULL ,
  `presentation` TEXT NULL DEFAULT NULL ,
  `logo` VARCHAR(100) NULL DEFAULT NULL ,
  `site` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1
COLLATE = latin1_swedish_ci;

INSERT INTO afup_forum_partenaires VALUES (NULL, 6,  5, 1, 'EuraTechnologies', 'Lieu de convergence des acteurs des projets et des innovations, le p�le d\'excellence EuraTechnologies a pour vocation de d�velopper le parc d\'activit�s � un �chelon international, d\'accompagner les entreprises du p�le dans leur d�veloppement technologique, commercial et strat�gique, de favoriser l\'�mergence de projets TIC et de nouveaux talents, et de proposer des outils et un environnement r�pondant aux besoins des entreprises.', 'logo_euratechnologies.png', 'http://www.euratechnologies.com/'),
(NULL, 6,  5, 2, 'P�le Nord', 'P�le Nord est l\'association d\'�diteurs de logiciels libre et open-source du Nord-Pas-de-Calais. Elle a pour objet la promotion et le d�veloppement des acteurs du Free/Libre and Open Source Software (FLOSS) de la r�gion Nord-Pas-de-Calais.', 'logo_polenord.png', 'http://www.polenord.info/'),
(NULL, 6,  5, 3, 'P�le Ubiquitaire', 'LE POLE UBIQUITAIRE est un r�seau informel pilot� par une gouvernance d\'experts qui s\'appuie sur un outil unique, pour installer la r�gion Nord-Pas-de-Calais comme leader d\'un �cosyst�me �conomique d\'avenir, l\'ubiquitaire.', 'logo_pole-ubiquitaire.png', 'http://www.pole-ubiquitaire.fr/'),
(NULL, 6,  5, 4, 'FrenchWeb', 'Le magazine des professionnels du net francophone, a pour mission de pr�senter les initiatives des acteurs fran�ais d\'internet. Il regroupe une communaut� de plus de 12 000 professionnels, entrepreneurs, experts.
L\'information multim�dia en continu, les interviews des experts, les fiches pratiques : rejoignez vite le CLUB Frenchweb pour tout savoir sur l\'internet B2B !', 'logo_frenchweb.png', 'http://frenchweb.fr/'),
(NULL, 6,  5, 5, 'TooLinux', 'TOOLINUX.com est un quotidien d\'information sur Linux et les logiciels Libres. G�n�raliste, il offre chaque jour une revue de presse en ligne et des articles traitant du mouvement opensource, de l\'�conomie du libre ainsi que des logiciels Linux ou multi-plateformes. Depuis l\'�t� 2006, TOOLINUX.com s\'ouvre � la probl�matique de l\'interop�rabilit� des solutions informatiques.', 'logo_toolinux.png', 'http://www.toolinux.com'),
(NULL, 6,  5, 6, 'Programmez !', 'Avec plus de 30.000 lecteurs mensuels, PROGRAMMEZ ! s\'est impos� comme un magazine de r�f�rence des d�veloppeurs.', 'logo_programmez.png', 'http://www.programmez.com/');
