let snackbars = document.getElementsByClassName('Snackbar-value');

for (let i = 0; i < snackbars.length; i++) {
    snackbars.item(i).querySelector('i').onclick = function () {
        this.parentElement.remove();
    }
}