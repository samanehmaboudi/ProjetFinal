/**
 * Gestion du partage de bouteille
 * 
 * Permet de générer un lien partageable pour une bouteille
 * et de copier ce lien dans le presse-papier.
 */

document.addEventListener('DOMContentLoaded', function() {
    const shareBtn = document.getElementById('shareBottleBtn');
    const shareModal = document.getElementById('shareModal');
    const shareModalClose = document.getElementById('shareModalClose');
    const shareModalContent = document.getElementById('shareModalContent');

    if (!shareBtn || !shareModal || !shareModalClose || !shareModalContent) {
        return; // Les éléments ne sont pas présents sur cette page
    }

    // Ouvrir le modal au clic sur le bouton Partager
    shareBtn.addEventListener('click', function() {
        const bouteilleId = this.getAttribute('data-bouteille-id');
        if (!bouteilleId) {
            console.error('ID de bouteille manquant');
            return;
        }

        // Afficher le modal avec un indicateur de chargement
        shareModal.classList.remove('hidden');
        shareModal.setAttribute('aria-hidden', 'false');
        shareModalContent.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
            </div>
        `;

        // Générer le lien de partage
        generateShareLink(bouteilleId);
    });

    // Fermer le modal
    function closeModal() {
        shareModal.classList.add('hidden');
        shareModal.setAttribute('aria-hidden', 'true');
    }

    shareModalClose.addEventListener('click', closeModal);

    // Fermer le modal en cliquant en dehors
    shareModal.addEventListener('click', function(e) {
        if (e.target === shareModal) {
            closeModal();
        }
    });

    // Fermer le modal avec la touche Échap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !shareModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    /**
     * Génère un lien de partage pour une bouteille
     */
    async function generateShareLink(bouteilleId) {
        try {
            const response = await fetch(`/api/partage/${bouteilleId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Erreur lors de la génération du lien');
            }

            if (data.success && data.url) {
                displayShareLink(data.url);
            } else {
                throw new Error('Réponse invalide du serveur');
            }
        } catch (error) {
            console.error('Erreur:', error);
            shareModalContent.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-600 mb-4">${error.message || 'Une erreur est survenue lors de la génération du lien.'}</p>
                    <button 
                        onclick="location.reload()"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-colors">
                        Réessayer
                    </button>
                </div>
            `;
        }
    }

    /**
     * Affiche le lien de partage avec le bouton de copie
     */
    function displayShareLink(url) {
        shareModalContent.innerHTML = `
            <div class="space-y-4">
                <p class="text-text-body mb-4">
                    Copiez ce lien pour partager cette bouteille :
                </p>
                
                <div class="flex items-center gap-2 p-3 bg-gray-50 border border-border-base rounded-lg">
                    <input 
                        type="text" 
                        id="shareLinkInput"
                        value="${url}" 
                        readonly
                        class="flex-1 bg-transparent border-none outline-none text-sm text-text-body"
                        aria-label="Lien de partage"
                    />
                    <button 
                        id="copyShareLinkBtn"
                        class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover active:bg-primary-active transition-colors duration-300"
                        aria-label="Copier le lien dans le presse-papier"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span>Copier</span>
                    </button>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button 
                        id="shareModalCloseBtn"
                        class="px-4 py-2 rounded-lg bg-body hover:shadow-none border-border-base border shadow-sm transition cursor-pointer"
                        aria-label="Fermer">
                        Fermer
                    </button>
                </div>
            </div>
        `;

        // Ajouter l'événement de copie
        const copyBtn = document.getElementById('copyShareLinkBtn');
        const shareLinkInput = document.getElementById('shareLinkInput');
        const closeBtn = document.getElementById('shareModalCloseBtn');

        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                copyToClipboard(url, shareLinkInput, copyBtn);
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
    }

    /**
     * Copie le lien dans le presse-papier
     */
    async function copyToClipboard(url, inputElement, buttonElement) {
        try {
            // Sélectionner le texte dans l'input
            inputElement.select();
            inputElement.setSelectionRange(0, 99999); // Pour mobile

            // Copier dans le presse-papier
            await navigator.clipboard.writeText(url);

            // Afficher un toast de confirmation
            if (window.showToast) {
                window.showToast('Lien copié dans le presse-papier', 'success');
            }

            // Changer temporairement le texte du bouton
            const originalText = buttonElement.innerHTML;
            buttonElement.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Copié !</span>
            `;
            buttonElement.classList.add('bg-green-600');
            buttonElement.classList.remove('bg-primary', 'hover:bg-primary-hover');

            // Restaurer après 2 secondes
            setTimeout(() => {
                buttonElement.innerHTML = originalText;
                buttonElement.classList.remove('bg-green-600');
                buttonElement.classList.add('bg-primary', 'hover:bg-primary-hover');
            }, 2000);

        } catch (error) {
            console.error('Erreur lors de la copie:', error);
            if (window.showToast) {
                window.showToast('Erreur lors de la copie du lien', 'error');
            }
        }
    }
});

