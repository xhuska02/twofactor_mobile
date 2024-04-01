document.addEventListener("DOMContentLoaded", function() {
    
    function submitForm() {
        document.getElementById("admin-2fa-form").submit();
    }

    setTimeout(submitForm, 5000);
    setInterval(submitForm, 2000);
});
