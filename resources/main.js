const languagePicker = document.querySelector('.mw-pt-languages');
if (languagePicker) {
    const languagesList = document.querySelector('.mw-pt-languages-list');
    if (languagesList.childNodes.length > 5 * 2) { // 2 because of the text nodes
        // display none languages list
        languagesList.classList.add('d-none');

        // add dropdown button
        const currentLanguage = document.querySelector('.mw-pt-languages-selected');
        var dropdown = document.createElement("div");
        dropdown.innerText = currentLanguage.innerText;
        dropdown.classList.add('mw-languages-dropdown');
        languagesList.parentElement.appendChild(dropdown);

        // add effect on the button to remove display none on the languages list
        dropdown.addEventListener('click', function() {
            const languagesList = document.querySelector('.mw-pt-languages-list');
            languagesList.classList.remove('d-none');
            dropdown.parentNode.removeChild(dropdown);
        });
    }
}
