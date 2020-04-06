<?php

/******************** Test Formateur *********************/

// echo "tot";
// $p = FormateurManager::findById(41);
// $s = FormateurManager::getByMatricule("88CN001");
// var_dump($p);
// var_dump($s);
// $p->setIdFormateur(null);
// $p->setPrenom("Claud");
// FormateurManager::add($p);
// FormateurManager::update($p);
// $tableau = FormateurManager::getList();
// foreach ($tableau as $unFormateur)
// {
//     echo $unFormateur->toString();
// }
// FormateurManager::delete($p);

/******************** Test Stagiaire *********************/

// $p = StagiaireManager::findById(18);
// $s = StagiaireManager::getByNumBenef("19124694");
// var_dump($p);
// var_dump($s);
// $p->setIdStagiaire(null);
// $p->setPrenom("Claud");
// StagiaireManager::add($p);
// StagiaireManager::update($p);
// $tableau = StagiaireManager::getList();
// foreach ($tableau as $unStagiaire)
// {
//     echo $unStagiaire->toString();
// }
// StagiaireManager::delete($p);

/******************** Test Formation *********************/

// $p = FormationManager::findById(14);
// var_dump($p);
// $p->setIdFormation(null);
// $p->setLibelleFormation("BBBBB");
// FormationManager::add($p);
// FormationManager::update($p); 
// var_dump($p);
// $tableau = FormationManager::getList();
// foreach ($tableau as $uneFormation)
// {
//     echo $uneFormation->toString();
// }
// FormationManager::delete($p);

/******************** Test Offre *********************/

// $p = OffreManager::findById(22);
// var_dump($p);
// $p->setIdOffre(null);
// $p->setNumOffre("1111111");
// OffreManager::add($p);
// OffreManager::update($p);
// var_dump($p);
// $tableau = OffreManager::getList();
// foreach ($tableau as $uneOffre)
// {
//     echo $uneOffre->toString();
// }
// OffreManager::delete($p);

/******************** Test pointage_par_semaine *********************/

// $p = Pointages_par_semainesManager::findById(1);
// var_dump($p);
// $tableau = Pointages_par_semainesManager::getList();
// foreach ($tableau as $unPointage)
// {
//     echo $unPointage->toString();
// }

/******************** Test stagiaire_par_offres *********************/

// $p = Stagiaires_par_offresManager::findById(167);
// var_dump($p);
// $tableau = Stagiaires_par_offresManager::getList();
// foreach ($tableau as $StagiaireparOffre)
// {
//     echo $StagiaireparOffre->toString();
// }