window.onclick = (event) => {
    let dropdown = document.getElementById('dropdown');
    let dropdownButton = document.getElementById('dropdownButton');

    if (event.target !== dropdownButton) {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
};