alter table tmp_esecutori add column `titolare_cf` varchar(50);
alter table tmp_esecutori add column `numero_anagrafiche` int(3) NULL default 0;
alter table tmp_esecutori add column `numero_partecipazioni` int(3) NULL default 0;
alter table tmp_esecutori add column `has_partecipazioni` int(1) NULL default 0;

alter table esecutori add column `titolare_cf` varchar(50);
alter table esecutori add column `numero_anagrafiche` int(3) NULL default 0;
alter table esecutori add column `numero_partecipazioni` int(3) NULL default 0;
alter table esecutori add column `has_partecipazioni` int(1) NULL default 0;

alter table tmp_anagrafiche_familiari add column `comune_residenza` varchar(255) DEFAULT NULL;
alter table tmp_anagrafiche_familiari add column `provincia_residenza` varchar(255) DEFAULT NULL;
alter table tmp_anagrafiche_familiari add column `via_residenza` varchar(255) DEFAULT NULL;
alter table tmp_anagrafiche_familiari add column `civico_residenza` varchar(255) DEFAULT NULL;
alter table tmp_anagrafiche_familiari add column `cap_residenza` varchar(255) DEFAULT NULL;
alter table tmp_anagrafiche_antimafia add column `antimafia_numero_familiari` int(2) NULL default 0;

alter table anagrafiche_familiari add column `comune_residenza` varchar(255) DEFAULT NULL;
alter table anagrafiche_familiari add column `provincia_residenza` varchar(255) DEFAULT NULL;
alter table anagrafiche_familiari add column `via_residenza` varchar(255) DEFAULT NULL;
alter table anagrafiche_familiari add column `civico_residenza` varchar(255) DEFAULT NULL;
alter table anagrafiche_familiari add column `cap_residenza` varchar(255) DEFAULT NULL;
alter table anagrafiche_antimafia add column `antimafia_numero_familiari` int(2) NULL default 0;
