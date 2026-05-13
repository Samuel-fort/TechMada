// ========================================
// UTILITAIRES ET FONCTIONS GÉNÉRALES
// ========================================

// Afficher un message flash (succès, erreur, info, attention)
function afficherFlash(message, type = 'success') {
  const container = document.body;
  
  // Créer la div du message
  const flash = document.createElement('div');
  flash.className = `flash flash-${type}`;
  flash.innerHTML = `
    <i class="bi bi-${getIconType(type)}"></i>
    ${message}
  `;
  
  // Insérer au début du contenu
  const content = container.querySelector('.content');
  if (content) {
    content.insertBefore(flash, content.firstChild);
  }
  
  // Disparaître après 4 secondes
  setTimeout(() => {
    flash.style.display = 'none';
  }, 4000);
}

// Obtenir l'icône selon le type
function getIconType(type) {
  const icons = {
    'success': 'check-circle-fill',
    'error': 'exclamation-circle-fill',
    'warn': 'exclamation-triangle-fill',
    'info': 'info-circle-fill'
  };
  return icons[type] || 'info-circle-fill';
}

// Confirmer avant une action
function confirmer(message) {
  return confirm(message);
}

// Formater une date (input type="date" -> format français)
function formatDateFR(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

// Calculer le nombre de jours entre deux dates
function calculerJours(debut, fin) {
  const d1 = new Date(debut);
  const d2 = new Date(fin);
  const diff = Math.abs(d2 - d1);
  return Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1; // +1 pour inclure les 2 jours
}

// Masquer/Afficher les éléments
function basculer(selecteur) {
  const el = document.querySelector(selecteur);
  if (el) {
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
  }
}

// Charger un formulaire via AJAX
function chargerFormulaireAjax(url, selecteur) {
  fetch(url)
    .then(response => response.text())
    .then(html => {
      const container = document.querySelector(selecteur);
      if (container) {
        container.innerHTML = html;
      }
    })
    .catch(error => console.error('Erreur:', error));
}

// Soumettre un formulaire via AJAX
function soumettreAjax(event, urlAction) {
  event.preventDefault();
  
  const formulaire = event.target;
  const donnees = new FormData(formulaire);
  
  fetch(urlAction, {
    method: 'POST',
    body: donnees
  })
    .then(response => response.json())
    .then(data => {
      if (data.succes) {
        afficherFlash(data.message, 'success');
        // Vider le formulaire
        formulaire.reset();
      } else {
        afficherFlash(data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Erreur:', error);
      afficherFlash('Une erreur est survenue', 'error');
    });
}

// Export pour utilisation dans d'autres fichiers
if (typeof module !== 'undefined' && module.exports) {
  module.exports = {
    afficherFlash,
    confirmer,
    formatDateFR,
    calculerJours,
    basculer,
    chargerFormulaireAjax,
    soumettreAjax
  };
}
