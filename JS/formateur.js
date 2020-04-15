var listSelect = document.querySelectorAll("select"); // on liste tous les SELECT
var listCheckbox = document.querySelectorAll('input[type=checkbox]'); // on liste les CHECKBOX 
var listMessages = document.querySelectorAll(".messageCheck"); // on liste les MESSAGES (zone qui sert à mettre un message d'erreur pres des checkboxs)
// var listStagiaire = document.querySelectorAll('[id^="idStagiaire"]'); // on liste les STAGIAIRES
//console.log(listStagiaire)
compteurCaseVide = [0, 0, 0, 0, 0]; //permet de repérer les combos qui ne serait pas remplis
//si le compteur correspondant à une checkbox n'est pas null, la validation est impossible
tabJournee=["lundi","mardi","mercredi","jeudi","vendredi"];

// ========================================================= SELECT PRESENCE (FORMATEUR)
for (let i = 0; i < listSelect.length; i++) {

    if (listSelect[i].value == "") { // de base, tous les champs non remplis sont rouges
        listSelect[i].style.border = "2px solid red";
        //on déduit de l'id, la journée correspondante (ex 6 pour jeudi)
        journee = listSelect[i].id.substring(5, 6)
        //on incremente le compteur correspondant
        compteurCaseVide[Math.trunc(journee / 2)]++;
    }

    listSelect[i].addEventListener("change", redBox); // et on ajoute un listener qui vérifie les changements
}

// Fonction : si la valeur de présence est non remplie alors la bordure est rouge, 
// si elle est remplie alors la bordure rouge disparaît
//on incremente et décrémente le compteur en conséquence
function redBox(e) {
    if (this.value == "") {
        this.style.border = "2px solid red";
        journee = e.target.id.substring(5, 6)
        compteurCaseVide[Math.trunc(journee / 2)]++;

    } else {
        this.style.border = "0"
        journee = e.target.id.substring(5, 6)
        compteurCaseVide[Math.trunc(journee / 2)]--;
    }
}

// =================================================================== CHECKBOX PRESENCE (FORMATEUR)
// on ajoute un listener pour capter tous le smodifications des checkbox
for (let i = 0; i < listCheckbox.length; i++) { // et on ajoute un listener qui appelle la fonction CHECKBOX
    listCheckbox[i].addEventListener("input", function () {
        checkBox(i) //message d'alerte
        checkSelect(i); //transforme select en input
    });
}

// Fonction : si la case est décochée, on affiche une alerte
function checkBox(id) {
    if (listCheckbox[id].checked == false) {
        listMessages[id].innerHTML = "Attention, vous modifiez une valeur pré-enregistrée !";
    } else {
        listMessages[id].innerHTML = "";
    }
}


// ============================================================================================= SELECT ET INPUT (FORMATEUR)
// Fonction : si la case est cochée, tous les SELECT deviennent des INPUT
function checkSelect(id) {
    //si la checkbox vient d'être cochée
    if (listCheckbox[id].checked == true) { 
        //si toutes les combos de la journee ne sont pas vides
        if (compteurCaseVide[id] <= 0) {
            //on liste les combo qui contiennent l'attribut correspondant à la journee
            j='select['+tabJournee[id]+']';
            listeCombo=document.querySelectorAll(j);
            //INFO : querySelectorAll(select) veut dire toutes les combos
            //       querySelectorAll(select['lundi']) veut dire toutes les combos qui ont un attribut lundi
                     
            for (let j = 0; j < listeCombo.length; j++) {
                //pour chaque combo, on la transforme en input
                selectToInput(listeCombo[j],tabJournee[id]);
            }
            //on enregistre les données (équivalent au clic sur le bouton enregistrer)
            document.forms["Formateur"].submit();
        } else {
            //on ne permet pas la validation car il reste des combos vides
            listCheckbox[id].checked = false;
            listMessages[id].innerHTML = "Attention, Tous les pointages ne sont pas saisis !";
        }
    } else {
        //on dévalide le pointage
        //on transforme les inputs en combo
        j='input['+tabJournee[id]+']';
        listeCombo=document.querySelectorAll(j);
        for (let j = 0; j < listeCombo.length; j++) {
            inputToSelect(listeCombo[j],tabJournee[id]);
        }
    }
}
// "transforme" les combos en inputs, en fait cache les combos et crée ds inputs
function selectToInput(select,$tagJournee) {
    var value = select.options[select.selectedIndex].text; // la valeur sélectionné du select
    var parent = select.parentNode; // la div parent
    select.style.display = "none"; // on cache le select
    //on crée un input visible avec la refPresence et un autre caché avec l'id
    parent.innerHTML += '<input readonly id="input' + select.id + '" name="' + select.id  + '  " value="' + value + '" '+$tagJournee+'  >'; // et on insère un input à la place
    parent.innerHTML += '<input type="hidden" id="in' + select.id + '" name="in' + select.id + '" value="' + select.options[select.selectedIndex].value + '" '+$tagJournee+'  >'; // et on insère un input à la place
}

//"transforme" les inputs en combos
//l'input passer en parametre est supprimé et on remet les combos visibles
function inputToSelect(input) {
    var parent = input.parentNode; // la div parent
    var select = parent.firstChild; // on cherche le select
    parent.removeChild(input);
    select.style.display = "block";
}