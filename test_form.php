<?php
/**
 * Test du formulaire de contact avec interface AJAX
 * Accès: http://localhost/clinique-marion%20-%20Copie/test_form.php
 */

header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Formulaire - Clinique Marion</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
        }
        .form-group {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        button {
            background-color: #667eea;
            color: white;
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            transition: background-color 0.3s;
        }
        button:hover:not(:disabled) {
            background-color: #5a67d8;
        }
        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .message-box {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .success-box {
            background-color: #c8e6c9;
            border: 1px solid #4caf50;
            color: #2e7d32;
        }
        .error-box {
            background-color: #ffcdd2;
            border: 1px solid #f44336;
            color: #c62828;
        }
        .info-box {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            color: #0d47a1;
        }
        .link {
            color: #667eea;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Formulaire de Contact</h1>
        <p class="subtitle">Test de l'interface AJAX du formulaire</p>

        <div id="resultMessage" style="display: none;"></div>

        <form id="testContactForm">
            <div class="form-group">
                <label for="nom">Nom complet:</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom complet" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="votre.email@example.com" required>
            </div>

            <div class="form-group">
                <label for="tel">Téléphone:</label>
                <input type="tel" id="tel" name="tel" placeholder="Votre numéro de téléphone" required>
            </div>

            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" placeholder="Votre message..." required></textarea>
            </div>

            <button type="submit" id="submitBtn">📧 Envoyer le message de test</button>
        </form>

        <div class="info-box" style="margin-top: 30px;">
            <strong>💡 Test du formulaire AJAX:</strong>
            <ul style="margin-top: 10px;">
                <li>Le formulaire utilise AJAX pour envoyer les données</li>
                <li>Pas de rechargement de page</li>
                <li>Message de succès/erreur affiché dans une modal</li>
                <li>Les données sont sauvegardées en base et les emails envoyés</li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <a href="contact.html" class="link">← Retour au formulaire complet</a>
        </div>
    </div>

    <!-- Message Modal (copié depuis contact.html) -->
    <div id="messageModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Message</h3>
                <span class="modal-close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="modalMessage"></p>
            </div>
            <div class="modal-footer">
                <button onclick="closeModal()" class="btn-primary">Fermer</button>
            </div>
        </div>
    </div>

    <script>
        // Gestionnaire de soumission du formulaire de test
        document.getElementById('testContactForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = document.getElementById('submitBtn');
            const originalText = submitButton.textContent;

            // Désactiver le bouton
            submitButton.disabled = true;
            submitButton.textContent = 'ENVOI EN COURS...';

            // Collecter les données
            const formData = new FormData(this);

            // Envoyer via AJAX
            fetch('process_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showModal('Message envoyé avec succès !', data.message, 'success');
                    document.getElementById('testContactForm').reset();
                } else {
                    showModal('Erreur', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showModal('Erreur', 'Une erreur inattendue s\'est produite. Veuillez réessayer.', 'error');
            })
            .finally(() => {
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        // Fonctions de modal
        function showModal(title, message, type = 'info') {
            const modal = document.getElementById('messageModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const modalContent = modal.querySelector('.modal-content');

            modalTitle.textContent = title;
            modalMessage.textContent = message;

            modalContent.className = 'modal-content';
            if (type === 'success') {
                modalContent.classList.add('modal-success');
            } else if (type === 'error') {
                modalContent.classList.add('modal-error');
            }

            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }

        // Fermer la modal en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('messageModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>