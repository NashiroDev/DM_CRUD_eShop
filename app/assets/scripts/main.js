
const buttonTShirt = document.querySelector('#TS'); // T-SHIRTS
const buttonPulls = document.querySelector('#PU'); // PULLS
const buttonPantalons = document.querySelector('#PA'); // PANTALONS
const buttonSousVetements = document.querySelector('#SO'); // SOUS-VETEMENTS
const buttonChaussetes = document.querySelector('#CHT'); // CHAUSSETES
const buttonChaussures = document.querySelector('#CHS'); // CHAUSSURES


buttonTShirt.addEventListener('click', () => {
    toggleDiv('.ts');
});
buttonPulls.addEventListener('click', () => {
    toggleDiv('.pu');
});
buttonPantalons.addEventListener('click', () => {
    toggleDiv('.pa');
});
buttonSousVetements.addEventListener('click', () => {
    toggleDiv('.so');
});
buttonChaussetes.addEventListener('click', () => {
    toggleDiv('.cht');
});
buttonChaussures.addEventListener('click', () => {
    toggleDiv('.chs');
});

//* affiche la div si elle est cachée et inversement
function toggleDiv(class_name) {
    var div = document.querySelector(class_name);
    if (div.style.display === 'none') {
      div.style.display = 'flex';
    } else {
      div.style.display = 'none';
    }
}

let prevScrollPos = window.pageYOffset;
  window.addEventListener('scroll', () => {

    let currentScrollPos = window.pageYOffset;

    if (prevScrollPos > currentScrollPos) {
      document.querySelector(".navbar").style.display = "flex";
    } else {
      document.querySelector(".navbar").style.display = "none";
    }
    prevScrollPos = currentScrollPos;
});

