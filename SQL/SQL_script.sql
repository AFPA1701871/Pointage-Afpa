#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `PointageAfpa` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `PointageAfpa`;

#------------------------------------------------------------
# Table: Stagiaire
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS stagiaire (
        idStagiaire Int  Auto_increment  PRIMARY KEY NOT NULL ,
        nom         Varchar (50) NOT NULL ,
        prenom      Varchar (50) NOT NULL ,
        motDePasse  Varchar (50) NOT NULL ,
        numBenef    Varchar (50) NOT NULL ,
        idOffre     Int NOT NULL ,
        role        Int NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Formateur
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS formateur (
        idFormateur Int  Auto_increment PRIMARY KEY  NOT NULL ,
        matricule   Varchar (50) NOT NULL ,
        nom         Varchar (50) NOT NULL ,
        prenom      Varchar (50) NOT NULL ,
        motDePasse  Varchar (50) NOT NULL ,
        role        Int NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Formation
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS formation (
        idFormation      Int  Auto_increment PRIMARY KEY NOT NULL ,
        codeFormation    Varchar (50) NOT NULL ,
        libelleFormation Varchar (100) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Semaine
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS semaine (
        idSemaine  Int Auto_increment PRIMARY KEY NOT NULL ,
        numSemaine Int NOT NULL ,
        mois       Varchar (50) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Journee
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS journee (
        idJournee   Int  Auto_increment  PRIMARY KEY NOT NULL ,
        jour        date NOT NULL ,
        demiJournee Varchar (50) NOT NULL ,
        idSemaine   Int NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Presence
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS presence (
        idPresence      Int  Auto_increment PRIMARY KEY  NOT NULL ,
        refPresence     Varchar (50) NOT NULL ,
        libellePresence Varchar (100) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: offre
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS offre (
        idOffre       Int  Auto_increment PRIMARY KEY  NOT NULL ,
        idFormation   Int NOT NULL ,
        idFormateur   Int NOT NULL ,
        numOffre      Varchar (50) NOT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# Table: Pointage
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS pointage (
        idPointage  Int  Auto_increment PRIMARY KEY  NOT NULL ,
        idStagiaire Int NOT NULL ,
        idJournee   Int NOT NULL ,
        idPresence  Int NOT NULL ,
        commentaire Varchar(200) ,
        validation  int default 0
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

#------------------------------------------------------------
# FOREIGN KEY stagiaire
#------------------------------------------------------------

    ALTER TABLE stagiaire ADD CONSTRAINT fk_stagiaire_semaine FOREIGN KEY(idOffre) REFERENCES offre(idOffre);

#------------------------------------------------------------
# FOREIGN KEY journee
#------------------------------------------------------------

    ALTER TABLE journee ADD CONSTRAINT fk_journee_semaine FOREIGN KEY(idSemaine) REFERENCES semaine(idSemaine);

#------------------------------------------------------------
# FOREIGN KEY offre
#------------------------------------------------------------

    ALTER TABLE offre ADD CONSTRAINT fk_offre_formation FOREIGN KEY(idFormation) REFERENCES formation(idFormation);
    ALTER TABLE offre ADD CONSTRAINT fk_offre_formateur FOREIGN KEY(idFormateur) REFERENCES formateur(idFormateur);

#------------------------------------------------------------
# FOREIGN KEY pointage
#------------------------------------------------------------

    ALTER TABLE pointage ADD CONSTRAINT fk_pointage_stagiaire FOREIGN KEY(idStagiaire) REFERENCES stagiaire(idStagiaire);
    ALTER TABLE pointage ADD CONSTRAINT fk_pointage_journee FOREIGN KEY(idJournee) REFERENCES journee(idJournee);
    ALTER TABLE pointage ADD CONSTRAINT fk_pointage_presence FOREIGN KEY(idPresence) REFERENCES Presence(idPresence);

#------------------------------------------------------------
# VIEWS 
#------------------------------------------------------------

DROP VIEW IF EXISTS pointages_par_semaines;
CREATE VIEW pointages_par_semaines AS SELECT
    p.idPointage, p.idStagiaire,p.idPresence, j.demiJournee, j.jour, j.idJournee, s.numSemaine, s.idSemaine, s.mois, p.commentaire, p.validation
FROM
    pointageAfpa.pointage AS p,
    pointageAfpa.journee AS j,
    pointageAfpa.semaine AS s
WHERE
    p.idJournee = j.idJournee AND j.idSemaine = s.idSemaine;

DROP VIEW IF EXISTS stagiaires_par_offres;
CREATE VIEW  stagiaires_par_offres AS SELECT
    s.idStagiaire, s.nom, s.prenom, s.numBenef, o.idOffre, o.numOffre, f.idFormation, f.codeFormation, f.libelleFormation
FROM
    pointageAfpa.stagiaire AS s,
    pointageAfpa.offre AS o,
    pointageAfpa.formation AS f
WHERE
    s.idOffre = o.idOffre AND o.idFormation = f.idFormation;