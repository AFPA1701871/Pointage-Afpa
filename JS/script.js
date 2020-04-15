/***********************************************/
/* Gestion de l'oeil sur la page de connexion  */
/***********************************************/
oeil=document.getElementById("oeil");

oeil.addEventListener("mousedown",montrerMDP);
oeil.addEventListener("mouseup",montrerMDP);


function montrerMDP(){
    if (document.getElementById('motDePasse').type=='text'){
        document.getElementById('motDePasse').type='password';
    }
    else{
        document.getElementById('motDePasse').type='text';
    }  
}

/****************************************************/
/* Gestion du placeHolder dans la page de connexion */
/****************************************************/
// on va chercher les elements "choix" 
choix=document.getElementById("choix"); 
choix.addEventListener("change",select); // action de changer la selection 

identifiant=document.getElementById("identifiant"); // on va chercher tout les identifiants
questionCircle=document.getElementsByClassName("fas fa-question-circle"); // on va chercher les elements de la classe question circle 


function select(){ // on cree la fonction qui va permettre de switché entre l'un et l'autre
    if (choix.value == "stagiaire"){ // quand notre choix est stagiaire 
        identifiant.setAttribute("placeholder", "Numéro bénéficiaire"); // on ecrit numero de beneficiaire 
        questionCircle[0].setAttribute("title","Numéro bénéficiaire");
        questionCircle[1].setAttribute("title","Numéro bénéficiaire");
    }
    else{
        identifiant.setAttribute("placeholder", "Matricule"); // sinon on ecrit matricule ( pour at et formation )
        questionCircle[0].setAttribute("title","Matricule");
        questionCircle[1].setAttribute("title","Matricule");
    }
}