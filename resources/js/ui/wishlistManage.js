// Gestion des quantités de bouteilles dans la liste d'achat
// Utilise la même logique que bottleQuantity.js du cellier
const wishlistButtons = document.querySelectorAll(".wishlist-qty-btn");
if (wishlistButtons.length) {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute("content") : "";
    
    // Ajout des écouteurs d'événements aux boutons
    wishlistButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const url = btn.dataset.url;
            const direction = btn.dataset.direction;
            const itemId = btn.dataset.itemId;

            const display = document.querySelector(
                `.wishlist-qty-display[data-item-id="${itemId}"]`
            );

            if (!url || !direction || !display) {
                console.error(
                    "Données manquantes pour la mise à jour de quantité."
                );
                return;
            }

            // Empêcher les clics multiples pendant qu'une requête est en cours
            if (display.dataset.loading === "true") {
                return;
            }

            const oldText = display.textContent;
            
            // Marquer comme en cours de chargement
            display.dataset.loading = "true";
            
            // Indicateur de chargement (Spinner)
            const spinnerTemplate = document.getElementById("spinner-inline-template");
            if (spinnerTemplate) {
                const clone = spinnerTemplate.content.cloneNode(true);
                display.innerHTML = "";
                display.appendChild(clone);
            } else {
                // Fallback if template doesn't exist
                display.innerHTML = `
                    <div 
                        class="inline-block w-6 h-6 border-2 border-neutral-200 border-t-primary rounded-full animate-spin" 
                        role="status" 
                        aria-label="Loading..."
                    ></div>
                `;
            }
            
            // Appel API pour mettre à jour la quantité (PATCH avec direction comme le cellier)
            fetch(url, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ direction }),
            })
                .then((res) => {
                    console.log("Réponse API quantité:", res.status);
                    if (!res.ok) {
                        throw new Error("Réponse serveur non OK");
                    }
                    return res.json();
                })
                .then((data) => {
                    console.log("Données JSON:", data);
                    if (data.success && typeof data.quantite !== "undefined") {
                        display.textContent = `${data.quantite}`;
                    } else {
                        display.textContent = oldText;
                    }
                    // Réinitialiser le flag de chargement
                    display.dataset.loading = "false";
                })
                .catch((err) => {
                    console.error("Erreur quantité:", err);
                    display.textContent = oldText;
                    // Réinitialiser le flag de chargement en cas d'erreur
                    display.dataset.loading = "false";
                });
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {

/* ============================================================
       CHECKBOX : MARQUER COMME ACHETÉ
       ============================================================ */
document.querySelectorAll(".wishlist-check-achete").forEach((checkbox) => {
    if (checkbox.dataset.jsBound === "true") return;
    checkbox.dataset.jsBound = "true";

    checkbox.addEventListener("change", () => {
        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("achete", checkbox.checked ? 1 : 0);

        fetch(checkbox.dataset.url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
            body: formData,
        })
            .then((res) => {
                // Style barré
                const label = checkbox.parentElement.querySelector("span");

                if (checkbox.checked) {
                    label.classList.add("line-through", "text-gray-400");
                } else {
                    label.classList.remove("line-through", "text-gray-400");
                }

                showToast("Statut mis à jour", "success");
            })
            .catch(() => showToast("Erreur réseau", "error"));
    });
});

async function refreshStats() {
    const response = await fetch("/api/listeAchat/stats");
    if (!response.ok) return;

    const stats = await response.json();

    const totalItemContainer = document.getElementById("totalItemContainer");
    const averagePriceContainer = document.getElementById(
        "averagePriceContainer"
    );
    const totalPriceContainer = document.getElementById("totalPriceContainer");

    totalItemContainer.textContent = stats.totalItem;
    averagePriceContainer.textContent = stats.averagePrice.toFixed(2) + " $";
    totalPriceContainer.textContent = stats.totalPrice.toFixed(2) + " $";
}
