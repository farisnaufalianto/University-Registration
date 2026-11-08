const form = document.getElementById("registrationForm");

if (form) {

    form.addEventListener("submit", function(event) {

        const name = form.elements["name"].value.trim();
        const email = form.elements["email"].value.trim();
        const studentId = form.elements["student_id"].value.trim();
        const major = form.elements["major"].value;

        if (!name || !email || !studentId || !major) {

            event.preventDefault();

            alert("Please complete all registration fields.");

            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {

            event.preventDefault();

            alert("Please enter a valid email address.");

        }

    });

}