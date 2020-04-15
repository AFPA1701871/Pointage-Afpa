//Quand le document est chargé
document.addEventListener("DOMContentLoaded", function() {
    
    /***************************************/
    /* Gestion du bouton Tout Selectionner */
    /***************************************/

    var selectAll = document.getElementById("selectAllOffres");

    //Ajout d'un event "click"
    selectAll.addEventListener("click", function(){
        // il y a 2 cas possibles
        //      aucune checkbox actuellement cochée, on les coche toutes
        //      une ou plusieurs checkbox cochée(s), on les décoche toutes

        //on recupere les Checkboxes des offres
        checkboxes = document.getElementsByClassName("checkboxOffre");

        //Booleen vrai si une offre est cochée, initialisé à faux
        var checked = false;

        //Parcours des checkboxes pour trouver si l'une est cochée  Peut etre optimisé par un while
        for(let i = 0; i < checkboxes.length; i++){

            //Si checkboxe cochée
            if(checkboxes[i].checked){

                //Booleen passe à vrai
                checked = true;
            }
        }

        //Parcours des checkboxes
        for(let i = 0; i < checkboxes.length; i++){

            //Si une des checkboxe est cochée
            if(checked){
                //Décocher les checkbox
                checkboxes[i].checked = false;
                //un effet CSS avec la class checked permet de mettre toute la ligne en bleu
                //on enleve cette classe
                checkboxes[i].parentElement.classList.remove("checked");
            
            }else{//Si aucune checkbox est cochée
                //Cocher les checkbox
                checkboxes[i].checked = true;
                checkboxes[i].parentElement.classList.add("checked");
            }
        }
        

    });
        /***************************************/
        /* Gestion des checkbox offres         */
        /***************************************/

    //Liste des offres
    var offres = document.getElementsByClassName("offre");
    
    for(let i = 0; i < offres.length; i++){
        //ajout d'un listener sur les spans qui contiennent les checkbox et le libellés
        //on peut cliquer sur la checkbox ou sur le libellé
        offres[i].addEventListener("click",function(){
            let checkbox = event.target.getElementsByClassName("checkboxOffre")[0];

            if(checkbox.checked){
                checkbox.checked = false;
                event.target.classList.remove("checked");
            }else{
                checkbox.checked = true;
                event.target.classList.add("checked");
            }
        })

    }
});