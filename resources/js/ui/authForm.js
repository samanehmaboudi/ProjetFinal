document.addEventListener("DOMContentLoaded", () => {
    const btnLogin = document.getElementById("loginBtn");
    const btnRegister = document.getElementById("registerBtn");

    const formLogin = document.getElementById("loginForm");
    const formRegister = document.getElementById("registerForm");

    const params = new URLSearchParams(window.location.search);
    const mode = params.get("mode");

    // mode login par default si recoit mode register
    if (mode === "register") {
        formLogin.classList.add("hidden");
        formRegister.classList.remove("hidden");

        btnRegister.classList.add("border-neutral-700");
        btnRegister.classList.remove("border-transparent");

        btnLogin.classList.add("border-transparent");
        btnLogin.classList.remove("border-neutral-700");
    }

    // ---- CLICK LOGIN ----
    btnLogin.addEventListener("click", () => {
        // Effacer les donnees des formulaires
        formLogin.reset();

        // Form switching
        formLogin.classList.remove("hidden");
        formRegister.classList.add("hidden");

        // Button styles
        btnLogin.classList.add("border-primary");
        btnLogin.classList.remove("border-neutral-600");
        btnLogin.classList.add("text-primary");
        btnLogin.classList.remove("text-neutral-600");

        btnRegister.classList.add("border-neutral-600");
        btnRegister.classList.remove("border-primary");
        btnRegister.classList.add("text-neutral-600");
        btnRegister.classList.remove("text-primary");
    });

    // ---- CLICK REGISTER ----
    btnRegister.addEventListener("click", () => {
        // Effacer les donnees des formulaires
        formRegister.reset();
        // Form switching
        formRegister.classList.remove("hidden");
        formLogin.classList.add("hidden");

        // Button styles
        btnRegister.classList.add("border-primary");
        btnRegister.classList.remove("border-neutral-600");
        btnRegister.classList.add("text-primary");
        btnRegister.classList.remove("text-neutral-600");

        btnLogin.classList.add("border-neutral-600");
        btnLogin.classList.remove("border-primary");
        btnLogin.classList.add("text-neutral-600");
        btnLogin.classList.remove("text-primary");
    });
});
