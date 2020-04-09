var listSelect = document.querySelectorAll("select"); // on liste tous les SELECT
var listCheckbox = document.querySelectorAll('input[type=checkbox]'); // on liste les CHECKBOX 
var listMessages = document.querySelectorAll(".messageCheck"); // on liste les MESSAGES
var listStagiaire = document.querySelectorAll('[id^="idStagiaire"]'); // on liste les STAGIAIRES
console.log(listStagiaire)
// ============================================================================================= SELECT PRESENCE (FORMATEUR)
for (let i = 0; i < listSelect.length; i++) {

    if (listSelect[i].value == "") { // de base, tous les champs non remplis sont rouges
        listSelect[i].style.border = "2px solid red";
    }

    listSelect[i].addEventListener("change", redBox); // et on ajoute un listener qui vérifie les changements
}

// Fonction : si la valeur de présence est non remplie alors la bordure est rouge, si elle est remplie alors la bordure rouge disparaît
function redBox() {
    if (this.value == "") {
        this.style.border = "2px solid red";
    }
    else {
        this.style.border = "0"
    }
}

// ============================================================================================= CHECKBOX PRESENCE (FORMATEUR)
for (let i = 0; i < listCheckbox.length; i++) { // et on ajoute un listener qui appelle la fonction CHECKBOX
    listCheckbox[i].addEventListener("input", function() {
        checkBox(i)
        checkSelect();
    });
}

// Fonction : si la case est décochée, on affiche une alerte
function checkBox(id) {
    if (listCheckbox[id].checked == false) {
        listMessages[id].innerHTML = "Attention, vous modifiez une valeur pré-enregistrée !";
    }
    else {
        listMessages[id].innerHTML = "";
    }
}


// ============================================================================================= SELECT ET INPUT (FORMATEUR)
// Fonction : si la case est cochée, tous les SELECT deviennent des INPUT
function checkSelect() {

    if (listCheckbox[4].checked == true) { // vendredi
        var listCombo = document.querySelectorAll('[id^="combo8"') // on liste les combobox du vendredi
        for (let j = 0; j < listStagiaire.length; j++) {
                selectToInput(listCombo[j].id);
        }
    }
    if (listCheckbox[4].checked == false) {
        var listCombo = document.querySelectorAll('[id^="combo8"') // on liste les combobox du vendredi
        for (let j = 0; j < listStagiaire.length; j++) {
                inputToSelect(listCombo[j].id);
        }
    }
    else if (listCheckbox[3].checked == true) { // jeudi
    }
    else if (listCheckbox[2].checked == true) { // mercredi
    }
    else if (listCheckbox[1].checked == true) { // mardi
    }
    else if (listCheckbox[0].checked == true) { // lundi
    }
}

function selectToInput(idSelect) {
    var select = document.getElementById(idSelect); // le select
    var value = select.options[select.selectedIndex].text;  // la valeur sélectionné du select

    var parent = select.parentNode; // la div parent

    select.id = "old"+idSelect;
    select.style.display = "none"; // on cache le select

    if (document.getElementById(idSelect) == undefined)
        parent.innerHTML += '<input readonly id="'+idSelect+'" name="'+idSelect+'" value="'+value+'">'; // et on insère un input à la place
}


function inputToSelect(idInput) {
    var input = document.getElementById(idInput); // l'input
    var parent = input.parentNode; // la div parent
    var select = parent.firstChild; // on cherche le select

    parent.removeChild(input);
    select.id = select.id.substring(3, select.id.length);
    select.style.display = "block";
}

