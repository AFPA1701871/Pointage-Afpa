<?php
for ($i=1;$i<10;$i++)
$p[] = PointageManager::findById(20+$i);

$p[2]->setIdPresence('3');
$p[4]->setIdPointage(null);
var_dump($p);
PointageManager::majPointage(1,1,$p);

//--------------------------JourneeManager---------------------------//

// $m = new Journee(["jour"=>"Mardi","demiJournee"=>"Apero_apres_midi","idSemaine"=>15]);
// JourneeManager::add($m); // Valider

// Update  :
// $m = JourneeManager::findById(2);
// $m->setJour("Jeudi");
// JourneeManager::update($m); /// Valider


// Delete :
// $m = JourneeManager::findById(2);
// JourneeManager::delete($m); // Valider

// // Get Liste  :
// $tableau = JourneeManager::getList();
// foreach ($tableau as $info)
// {
//     echo $info->toString();
// }
// Valider


//--------------------------PointageManager--------------------------//

// $m = new Pointage(["idStagiaire"=>2,"idJournee"=>3,"idPresence"=>1,"commentaire"=>"J'aime les pates","validation"=>"OK"]);
// PointageManager::add($m); // Valider

//Update:
// $m = PointageManager::findById(4);
// $m->setCommentaire("J'aime le piment d'espelette");
// PointageManager::update($m); //Valider


// //Delete  :
// $m = PointageManager::findById(4);
// PointageManager::delete($m); // Valider

// //Get Liste :
// $tableau = PointageManager::getList();
// foreach ($tableau as $info)
// {
//     echo $info->toString();
// }
// Valider


//--------------------------PresenceManager--------------------------//
// $m = new Presence(["refPresence"=>"ppda","libellePresence"=>"Lala"]);
// PresenceManager::add($m); // Valider

//Update :
// $m = PresenceManager::findById(2);
// $m->setRefPresence("TOU");
// PresenceManager::update($m); //Valider


// // //Delete  :
// $m = PresenceManager::findById(2);
// PresenceManager::delete($m); // Valider

// // //Get Liste E :
// $tableau = PresenceManager::getList();
// foreach ($tableau as $info)
// {
//     echo $info->toString();
// }
// Valider


//--------------------------SemaineManager---------------------------//
// $m = new Semaine(["mois"=>"Juin","numSemaine"=>"6"]);
// SemaineManager::add($m); // Valider

//Update  :
// $m = SemaineManager::findById(16);
// $m->setMois("Juillet");
// SemaineManager::update($m); //Valider


// // //Delete  :
// $m = SemaineManager::findById(16);
// SemaineManager::delete($m); // Valider

// //Get Liste  :
// $tableau = SemaineManager::getList();
// foreach ($tableau as $info)
// {
//     echo $info->toString();
// }
// Valider