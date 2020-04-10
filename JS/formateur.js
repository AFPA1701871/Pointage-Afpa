var listSelect = document.querySelectorAll("select"); // on liste tous les SELECT
var listCheckbox = document.querySelectorAll('input[type=checkbox]'); // on liste les CHECKBOX 
var listMessages = document.querySelectorAll(".messageCheck"); // on liste les MESSAGES
var listStagiaire = document.querySelectorAll('[id^="idStagiaire"]'); // on liste les STAGIAIRES
console.log(listStagiaire)
compteurCaseVide = [0, 0, 0, 0, 0];
tabJournee=["lundi","mardi","mercredi","jeudi","vendredi"];

// ============================================================================================= SELECT PRESENCE (FORMATEUR)
for (let i = 0; i < listSelect.length; i++) {

    if (listSelect[i].value == "") { // de base, tous les champs non remplis sont rouges
        listSelect[i].style.border = "2px solid red";
        journee = listSelect[i].id.substring(5, 6)
        compteurCaseVide[Math.trunc(journee / 2)]++;
    }

    listSelect[i].addEventListener("change", redBox); // et on ajoute un listener qui vérifie les changements
}

// Fonction : si la valeur de présence est non remplie alors la bordure est rouge, si elle est remplie alors la bordure rouge disparaît
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

// ============================================================================================= CHECKBOX PRESENCE (FORMATEUR)
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

    idSelect = id * 2;
    if (listCheckbox[id].checked == true) { // vendredi
        if (compteurCaseVide[id] <= 0) {
            //on liste les combo qui contienne l'attribut correspondant à la journee
            j='select['+tabJournee[id]+']';
            listeCombo=document.querySelectorAll(j);
            for (let j = 0; j < listeCombo.length; j++) {
                selectToInput(listeCombo[j],tabJournee[id]);
            }
           // document.getElementById("Formateur").submit();
         document.forms["Formateur"].submit();
        } else {
            listCheckbox[id].checked = false;
            listMessages[id].innerHTML = "Attention, Tous les pointages ne sont pas saisis !";
        }
    } else {
        j='input['+tabJournee[id]+']';
        listeCombo=document.querySelectorAll(j);
        for (let j = 0; j < listeCombo.length; j++) {
            inputToSelect(listeCombo[j],tabJournee[id]);
        }
    }
}

function selectToInput(select,$tagJournee) {
    var value = select.options[select.selectedIndex].text; // la valeur sélectionné du select
    var parent = select.parentNode; // la div parent
    select.style.display = "none"; // on cache le select
    parent.innerHTML += '<input readonly id="input' + select.id + '" name="' + idSelect + '  " value="' + value + '" '+$tagJournee+'  >'; // et on insère un input à la place
    parent.innerHTML += '<input type="hidden" id="in' + select.id + '" name="in' + select.id + '" value="' + select.options[select.selectedIndex].value + '" '+$tagJournee+'  >'; // et on insère un input à la place
}

/*********************************AJOUTER un displayNONE surles combo qu'en le pointage arrive deja valide */
function inputToSelect(input) {
    var parent = input.parentNode; // la div parent
    var select = parent.firstChild; // on cherche le select

    parent.removeChild(input);
    select.style.display = "block";
}