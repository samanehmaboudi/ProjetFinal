// Gestion du toggle actif/inactif pour les utilisateurs admin
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les formulaires de toggle actif/inactif
    document.querySelectorAll('form[action*="toggle-active"]').forEach((form) => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const url = form.action;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalLabel = submitBtn?.textContent.trim();
            
            if (!csrfToken) {
                if (window.showToast) {
                    showToast("Erreur de sécurité. Veuillez recharger la page.", "error");
                }
                return;
            }

            // Désactiver le bouton pendant la requête
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add("opacity-50", "cursor-not-allowed");
            }

            try {
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    },
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Afficher le toast de succès
                    if (window.showToast) {
                        showToast(data.message || "Statut de l'usager mis à jour.", "success");
                    }
                    
                    // Mettre à jour le label du bouton si disponible
                    if (submitBtn && data.is_active !== undefined) {
                        // Attendre un peu pour que le toast soit visible, puis recharger
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Recharger la page après un court délai
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    // Afficher le toast d'erreur
                    if (window.showToast) {
                        showToast(data.message || "Erreur lors de la mise à jour.", "error");
                    }
                    
                    // Réactiver le bouton
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
                    }
                }
            } catch (error) {
                console.error("Erreur:", error);
                if (window.showToast) {
                    showToast("Erreur réseau. Veuillez réessayer.", "error");
                }
                
                // Réactiver le bouton
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove("opacity-50", "cursor-not-allowed");
                }
            }
        });
    });
});

