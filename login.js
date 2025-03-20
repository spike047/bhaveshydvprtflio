document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("login-form")?.addEventListener("submit", function (event) {
        event.preventDefault();
        
        let formData = new FormData(this);
        
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Login response:", data);
            if (data.success) {
                window.location.href = data.redirect; // Redirect to courses.html
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Login error:", error);
            alert("An error occurred. Please try again.");
        });
    });

    document.getElementById("register-form")?.addEventListener("submit", function (event) {
        event.preventDefault();
        
        let formData = new FormData(this);
        
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Register response:", data);
            if (data.success) {
                window.location.href = data.redirect; // Redirect to courses.html
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Registration error:", error);
            alert("An error occurred. Please try again.");
        });
    });
});
