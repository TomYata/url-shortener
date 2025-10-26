
import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';

import '../css/app.css';


document.addEventListener('DOMContentLoaded', function () {
    // Bouton "Copier"
	document.querySelectorAll('.btn-copy').forEach(function (btn) {
		btn.addEventListener('click', function () {
			const url = btn.getAttribute('data-url');
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url);
			} else {
				// Fallback pour les navigateurs non supportés
				const tempInput = document.createElement('input');
				tempInput.value = url;
				document.body.appendChild(tempInput);
				tempInput.select();
				document.execCommand('copy');
				document.body.removeChild(tempInput);
			}
			// Changer le texte du bouton pour indiquer la copie
			const originalText = btn.textContent;
			btn.textContent = 'Copié !';
			setTimeout(() => {
				btn.textContent = originalText;
			}, 1500);
		});
	});
    // Boutton "Supprimer" avec confirmation
    document.querySelectorAll('.form-delete-url').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Supprimer cette URL ?')) {
                e.preventDefault();
            }
        });
    });
});
