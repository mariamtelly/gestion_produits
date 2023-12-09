const buttons = document.querySelectorAll('.btn .btn-primary .btn-number');

buttons.forEach(button => {
    button.addEventListener('click', function() {
        const action = this.getAttribute('data-action');
        const produitId = this.getAttribute('data-product-id');
        updateCart(action, produitId);
    });
});

function updateCart(action, produitId) {
    // Effectuez une requête AJAX pour mettre à jour la quantité dans le panier
    fetch('/update-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ produitId, action }),
    })
    .then(response => response.json())
    .then(data => {
        // Mettre à jour les éléments HTML pertinents
        // const quantityElement = document.querySelector(`input[name="quant[${data.produitId}]"]`);
        // const totalAmountElement = document.querySelector('.total-amount span');
        // const sousTotal = document.querySelector('#sous-total span');

        // if (quantityElement) {
        //     quantityElement.value = data.nouvelleQte;
        // }

        // if (totalAmountElement) {
        //     totalAmountElement.textContent = data.nouveauTotal;
        // }

        // if(sousTotal) {
        //     sousTotal.textContent = data.sousTotal;
        // }
    })
    .catch(error => {
        console.log(error);
    });
}