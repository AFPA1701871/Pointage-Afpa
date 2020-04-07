
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