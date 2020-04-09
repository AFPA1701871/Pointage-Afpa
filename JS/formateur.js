var listSelect = document.querySelectorAll("select"); // on liste tous les SELECT et si il n'est pas rempli, sa bordure devient rouge
var listCheckbox = document.querySelectorAll('input[type=checkbox]'); // on liste toutes les CHECKBOX 
var listMessages = document.querySelectorAll(".messageCheck"); // on liste aussi les MESSAGES

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

