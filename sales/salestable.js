const recordsPerPage = 10;
let currentPage = 1;
let tableData = []; // Load data dynamically or set an array of table rows.

function loadTableData() {
    const startIndex = (currentPage - 1) * recordsPerPage;
    const endIndex = startIndex + recordsPerPage;
    const currentRecords = tableData.slice(startIndex, endIndex);

    const tableBody = document.getElementById("table-data");
    tableBody.innerHTML = ""; // Clear current rows

    currentRecords.forEach(record => {
        const row = `<tr>
                        <td>${record.id}</td>
                        <td>${record.name}</td>
                        <td>${record.quantity}</td>
                    </tr>`;
        tableBody.insertAdjacentHTML("beforeend", row);
    });

    updatePageInfo();
}

function updatePageInfo() {
    const pageInfo = document.getElementById("page-info");
    const totalPages = Math.ceil(tableData.length / recordsPerPage);
    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
}

document.getElementById("prev-btn").addEventListener("click", () => {
    if (currentPage > 1) {
        currentPage--;
        loadTableData();
    }
});

document.getElementById("next-btn").addEventListener("click", () => {
    if (currentPage * recordsPerPage < tableData.length) {
        currentPage++;
        loadTableData();
    }
});

// Example data load and initial display
tableData = [/* Load data here */];
loadTableData();
