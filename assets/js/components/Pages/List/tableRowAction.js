let rows = document.getElementsByClassName('Table-row');

for (let i = 0; i < rows.length; i++) {
    rows.item(i).onclick = function () {
        this.querySelector('a').click();
    }
}