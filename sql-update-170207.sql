alter table anagrafiche_antimafia add column is_giuridica int(1) not null default 0;
alter table anagrafiche_antimafia add column giuridica_ragione_sociale varchar(250) null;
alter table anagrafiche_antimafia add column giuridica_partita_iva varchar(250) null;
alter table anagrafiche_antimafia add column giuridica_codice_fiscale varchar(250) null;

alter table tmp_anagrafiche_antimafia add column is_giuridica int(1) not null default 0;
alter table tmp_anagrafiche_antimafia add column giuridica_ragione_sociale varchar(250) null;
alter table tmp_anagrafiche_antimafia add column giuridica_partita_iva varchar(250) null;
alter table tmp_anagrafiche_antimafia add column giuridica_codice_fiscale varchar(250) null;

alter table tmp_anagrafiche_antimafia change antimafia_nome antimafia_nome varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_comune_nascita antimafia_comune_nascita varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_data_nascita antimafia_data_nascita datetime;
alter table tmp_anagrafiche_antimafia change antimafia_comune_residenza antimafia_comune_residenza varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_provincia_residenza antimafia_provincia_residenza varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_via_residenza antimafia_via_residenza varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_civico_residenza antimafia_civico_residenza varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_cf antimafia_cf varchar(255);
alter table tmp_anagrafiche_antimafia change antimafia_carica_sociale antimafia_carica_sociale varchar(250);
alter table tmp_anagrafiche_antimafia change antimafia_cognome antimafia_cognome varchar(250);
alter table tmp_anagrafiche_antimafia change antimafia_provincia_nascita antimafia_provincia_nascita varchar(250);
alter table tmp_anagrafiche_antimafia change is_giuridica is_giuridica int(1);
alter table tmp_anagrafiche_antimafia change giuridica_ragione_sociale giuridica_ragione_sociale varchar(250);
alter table tmp_anagrafiche_antimafia change giuridica_partita_iva giuridica_partita_iva varchar(250);
alter table tmp_anagrafiche_antimafia change giuridica_codice_fiscale giuridica_codice_fiscale varchar(250);

alter table anagrafiche_antimafia change antimafia_nome antimafia_nome varchar(255);
alter table anagrafiche_antimafia change antimafia_comune_nascita antimafia_comune_nascita varchar(255);
alter table anagrafiche_antimafia change antimafia_data_nascita antimafia_data_nascita datetime;
alter table anagrafiche_antimafia change antimafia_comune_residenza antimafia_comune_residenza varchar(255);
alter table anagrafiche_antimafia change antimafia_provincia_residenza antimafia_provincia_residenza varchar(255);
alter table anagrafiche_antimafia change antimafia_via_residenza antimafia_via_residenza varchar(255);
alter table anagrafiche_antimafia change antimafia_civico_residenza antimafia_civico_residenza varchar(255);
alter table anagrafiche_antimafia change antimafia_cf antimafia_cf varchar(255);
alter table anagrafiche_antimafia change antimafia_carica_sociale antimafia_carica_sociale varchar(250);
alter table anagrafiche_antimafia change antimafia_cognome antimafia_cognome varchar(250);
alter table anagrafiche_antimafia change antimafia_provincia_nascita antimafia_provincia_nascita varchar(250);
alter table anagrafiche_antimafia change is_giuridica is_giuridica int(1);
alter table anagrafiche_antimafia change giuridica_ragione_sociale giuridica_ragione_sociale varchar(250);
alter table anagrafiche_antimafia change giuridica_partita_iva giuridica_partita_iva varchar(250);
alter table anagrafiche_antimafia change giuridica_codice_fiscale giuridica_codice_fiscale varchar(250);

alter table esecutori add column is_sent int(1) null default 0;
