document.getElementById('aktionForm').addEventListener('submit', function(e) {
    var name = document.getElementById('name').value.trim();
    var email = document.getElementById('email').value.trim();
    if (name.length < 2) {
        alert('Bitte geben Sie einen gültigen Namen ein.');
        e.preventDefault();
    }
    if (!email.includes('@')) {
        alert('Bitte geben Sie eine gültige E-Mail-Adresse ein.');
        e.preventDefault();
    }
});