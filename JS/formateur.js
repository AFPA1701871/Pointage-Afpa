// ============================================================================================= SELECT PRESENCE (FORMATEUR)
// on liste tous les SELECT et si il n'est pas rempli, sa bordure devient rouge
var listSelect = document.querySelectorAll("select");
for (let i = 0; i < listSelect.length; i++) {

    if (listSelect[i].value == "") { // de base, tous les champs non remplis sont rouges
        console.log("0")
        listSelect[i].style.border = "2px solid red";
    }

    listSelect[i].addEventListener("change", redBox); // et on ajoute un listener qui vérifie les changements
}

// Fonction : si la valeur de présence est non remplie alors la bordure est rouge, si elle est remplie alors la bordure rouge disparaît
function redBox() {
    if (this.value == "") {
        console.log("1")
        this.style.border = "2px solid red";
    }
    else {
        console.log('2')
        this.style.border = "0"
    }
}

// ============================================================================================= CHECKBOX PRESENCE (FORMATEUR)
// on liste toutes les CHECKBOX et on ajoute un listener qui appelle la fonction CHECKBOX
var listCheckbox = document.querySelectorAll('input[type=checkbox]');
for (let i = 0; i < listCheckbox.length; i++) {
    listCheckbox[i].addEventListener("input", checkBox);
}

// Fonction : si la case est décochée, on affiche une alerte
function checkBox() {
    if (this.checked == false) {
        alert("Attention, vous modifiez une valeur déjà pré-enregistrée!");
    }
}