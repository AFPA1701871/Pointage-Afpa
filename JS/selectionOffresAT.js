//Quand le document est chargé
document.addEventListener("DOMContentLoaded", function() {
    
    //Bouton "Tout selectionner"
    var selectAll = document.getElementById("selectAllOffres");

    //Ajout d'un event "click"
    selectAll.addEventListener("click", function(){

        //Checkboxes des offres
        checkboxes = document.getElementsByClassName("checkboxOffre");

        //Booleen vrai si une offre est cochée, initialisé à faux
        var checked = false;

        //Parcours des checkboxes
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
                checkboxes[i].parentElement.classList.remove("checked");
            //Si aucue checkbox est cochée
            }else{
                //Cocher les checkbox
                checkboxes[i].checked = true;
                checkboxes[i].parentElement.classList.add("checked");
            }
        }
        

    });

    //Liste des offres
    var offres = document.getElementsByClassName("offre");
    
    for(let i = 0; i < offres.length; i++){
    
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

    //Liste des checkboxes
    var checkboxes = document.getElementsByClassName("checkboxOffre");
    
    for(let i = 0; i < checkboxes.length; i++){
    
        checkboxes[i].addEventListener("click",function(){
            let checkbox = event.target;

            if(checkbox.checked){
                checkbox.parentElement.classList.add("checked");
            }else{
                checkbox.parentElement.classList.remove("checked");
            }
        })

    }
});