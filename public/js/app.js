function afficherFlash(message, type = 'success') {
  const container = document.body;
  
  const flash = document.createElement('div');
  flash.className = `flash flash-${type}`;
  flash.innerHTML = `
    <i class="bi bi-${getIconType(type)}"></i>
    ${message}
  `;
  
  const content = container.querySelector('.content');
  if (content) {
    content.insertBefore(flash, content.firstChild);
  }
  
  setTimeout(() => {
    flash.style.display = 'none';
  }, 4000);
}

function getIconType(type) {
  const icons = {
    'success': 'check-circle-fill',
    'error': 'exclamation-circle-fill',
    'warn': 'exclamation-triangle-fill',
    'info': 'info-circle-fill'
  };
  return icons[type] || 'info-circle-fill';
}

function confirmer(message) {
  return confirm(message);
}

function formatDateFR(dateStr) {
  const date = new Date(dateStr);
  return date.toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

function calculerJours(debut, fin) {
  const d1 = new Date(debut);
  const d2 = new Date(fin);
  const diff = Math.abs(d2 - d1);
  return Math.ceil(diff / (1000 * 60 * 60 * 24)) + 1;
}

function basculer(selecteur) {
  const el = document.querySelector(selecteur);
  if (el) {
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
  }
}

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
