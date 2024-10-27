// ********** Inventory **********
// Selecting the elements
const addInventoryButton = document.querySelector('.add-inventory');
const addInventoryModal = document.querySelector('.add-inventory-modal');
const addInventoryModalOverlay = document.querySelector('.add-inventory-modal-overlay');

// Show the modal and overlay when the "Add Members" button is clicked
addInventoryButton.addEventListener('click', () => {
    addInventoryModal.classList.add('show');
    addInventoryModalOverlay.classList.add('show');
});

// Hide the modal and overlay when the overlay is clicked
addInventoryModalOverlay.addEventListener('click', () => {
    addInventoryModal.classList.remove('show');
    addInventoryModalOverlay.classList.remove('show');
});

// Selecting elements for the Edit Member functionality
const editInventoryButton = document.querySelectorAll('.edit-inventory');
const editInventoryModal = document.querySelector('.edit-inventory-modal');
const editInventoryModalOverlay = document.querySelector('.edit-inventory-modal-overlay');

// Loop through each edit button and add a click event listener
editInventoryButton.forEach(button => {
        button.addEventListener('click', function() {
            const ItemId = this.getAttribute('data-inventory-id');
            document.getElementById('editItemID').value = ItemId;
        // Show the edit modal
        editInventoryModal.classList.add('show');
        editInventoryModalOverlay.classList.add('show');
    });
});

// Hide the edit modal when the overlay is clicked
editInventoryModalOverlay.addEventListener('click', () => {
    editInventoryModal.classList.remove('show');
    editInventoryModalOverlay.classList.remove('show');
});