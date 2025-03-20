// Toggle Between Login and Register Forms
const container = document.getElementById("container");
const registerbtn = document.getElementById("register");
const loginbtn = document.getElementById("login");

registerbtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginbtn.addEventListener("click", () => {
  container.classList.remove("active");
});

document.addEventListener("DOMContentLoaded", function () {

  // 🔹 Handle Signup Submission
  document.querySelector(".sign-up form").addEventListener("submit", function (event) {
      event.preventDefault();

      let name = document.querySelector(".sign-up input[name='name']").value.trim();
      let email = document.querySelector(".sign-up input[name='email']").value.trim();
      let password = document.querySelector(".sign-up input[name='password']").value.trim();

      if (!name || !email || !password) {
          alert("All fields are required!");
          return;
      }

      let formData = JSON.stringify({ name, email, password });

      console.log("🔹 Sending Signup Data:", formData); // Debugging Output

      fetch("http://localhost/tuningarc_api/db.php", { // 🔹 Correct endpoint
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: formData
      })
      .then(response => response.json())
      .then(data => {
          console.log("🔹 Signup Response:", data);
          if (data.success) {
              alert("Signup successful! You can now log in.");
              container.classList.remove("active"); // Switch to login
          } else {
              alert(data.error);
          }
      })
      .catch(error => console.error("Signup Error:", error));
  });

  // 🔹 Handle Login Submission
  document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("login-form"); // Ensure form exists
    const emailInput = document.getElementById("email"); // Ensure email field exists
    const passwordInput = document.getElementById("password"); // Ensure password field exists

    if (!loginForm || !emailInput || !passwordInput) {
        console.error("❌ Error: Login form or input fields not found in the DOM.");
        return; // Stop execution if elements are missing
    }

    loginForm.addEventListener("submit", function (e) {
        e.preventDefault(); // Prevent page reload

        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();

        if (!email || !password) {
            alert("Email and password are required!");
            return;
        }

        fetch("http://localhost/tuningarc_api/login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        })
        .then(response => response.json())
        .then(data => {
            console.log("Login Response:", data);
            if (data.success) {
                alert("Login successful!");
                window.location.href = "courses.html";
            } else {
                alert("Login failed: " + data.message);
            }
        })
        .catch(error => console.error("Login error:", error));
    });
});

});
