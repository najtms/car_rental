$(document).ready(function() {
    // Function to load data into modal
    function loadModalData() {
        // Make AJAX call
        $.ajax({
            url: "your_backend_script.php", // Path to your server-side script
            method: "GET",
            dataType: "html", // Expected data type
            success: function(response) {
                // Populate modal with retrieved data
                $("#modalContent").html(response);
                // Display modal
                $("#AudiRS6").modal("show");
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
            }
        });
    }

    // Event listener for button click
    $("#SubmitTwo").click(function() {
        // Call function to load data into modal
        loadModalData();
    });
});
