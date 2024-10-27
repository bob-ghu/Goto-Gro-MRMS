// Selecting the elements
const addSalesButton = document.querySelector('.add-sales');
const modal = document.querySelector('.modal');
const modalOverlay = document.querySelector('.modal-overlay');

// Show the modal and overlay when the "Add Members" button is clicked
addSalesButton.addEventListener('click', () => {
    modal.classList.add('show');
    modalOverlay.classList.add('show');
});

// Hide the modal and overlay when the overlay is clicked
modalOverlay.addEventListener('click', () => {
    modal.classList.remove('show');
    modalOverlay.classList.remove('show');
});

// Selecting elements for the Edit Member functionality
const editSalesButtons = document.querySelectorAll('.edit-sales'); // Assuming you have edit buttons for each member
const editModal = document.querySelector('.edit-modal'); // Correctly using ID without #
const editModalOverlay = document.querySelector('.edit-modal-overlay');

// Loop through each edit button and add a click event listener
editSalesButtons.forEach(button => {
    button.addEventListener('click', function() {
        const salesId = this.getAttribute('data-sales-id');
        document.getElementById('editSalesID').value = salesId;

        // Show the edit modal
        editModal.classList.add('show');
        editModalOverlay.classList.add('show');
    });
});

// Hide the edit modal when the overlay is clicked
editModalOverlay.addEventListener('click', () => {
    editModal.classList.remove('show');
    editModalOverlay.classList.remove('show');
});
