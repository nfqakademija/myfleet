window.onclick = function(event) {
    let taskModal = document.getElementById('task-modal');
    let eventModal = document.getElementById('event-modal');
    let expenseModal = document.getElementById('expense-modal');

    if (event.target === taskModal) {
        taskModal.style.display = "none";
    } else if (event.target === eventModal) {
        eventModal.style.display = "none";
    } else if (event.target === expenseModal) {
        expenseModal.style.display = "none";
    }
};

export default function (modalId) {
    let modal = document.getElementById(modalId);
    modal.style.display = "none";
};