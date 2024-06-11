document.getElementById('myForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Get form data
    var formData = new FormData(this);
    // Convert form data to JSON
    var formJSON = Object.fromEntries(formData.entries());
    // Open a new tab with the form data
    window.open('data:application/json,' + encodeURIComponent(JSON.stringify(formJSON)), '_blank');
});