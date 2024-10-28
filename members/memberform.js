// Selecting the elements
const addMemberButton = document.querySelector('.add-member');
const modal = document.querySelector('.modal');
const modalOverlay = document.querySelector('.modal-overlay');

// Show the modal and overlay when the "Add Members" button is clicked
addMemberButton.addEventListener('click', () => {
    modal.classList.add('show');
    modalOverlay.classList.add('show');
});

// Hide the modal and overlay when the overlay is clicked
modalOverlay.addEventListener('click', () => {
    modal.classList.remove('show');
    modalOverlay.classList.remove('show');
});

// Selecting elements for the Edit Member functionality
const editMemberButtons = document.querySelectorAll('.edit-member'); // Assuming you have edit buttons for each member
const editModal = document.querySelector('.edit-modal'); // Correctly using ID without #
const editModalOverlay = document.querySelector('.edit-modal-overlay');

// Loop through each edit button and add a click event listener
editMemberButtons.forEach(button => {
    button.addEventListener('click', function() {
        const memberId = this.getAttribute('data-member-id');
        document.getElementById('editMemberID').value = memberId;

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

