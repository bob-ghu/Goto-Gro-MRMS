// ********** feedback **********
// Selecting the elements
const addfeedbackButton = document.querySelector('.add-feedback');
const addfeedbackModal = document.querySelector('.add-feedback-modal');
const addfeedbackModalOverlay = document.querySelector('.add-feedback-modal-overlay');

// Show the modal and overlay when the "Add Members" button is clicked
addfeedbackButton.addEventListener('click', () => {
    addfeedbackModal.classList.add('show');
    addfeedbackModalOverlay.classList.add('show');
});

// Hide the modal and overlay when the overlay is clicked
addfeedbackModalOverlay.addEventListener('click', () => {
    addfeedbackModal.classList.remove('show');
    addfeedbackModalOverlay.classList.remove('show');
});

