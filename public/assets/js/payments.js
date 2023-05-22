document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementsByClassName('formPayment');
    for( let i = 0; i < form.length; i++){
        form[i].addEventListener('submit',  e => {
            e.preventDefault();
            const data = Object.fromEntries( new FormData( e.target ) );
            addPayment( data, e.target.action, data._token );
        });
    }
    
});

function addPayment( data, ruta, token ){
    const options = {
        method: "GET",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token
        }
    };

    fetch(ruta, options)
    .then(response => response.text())
    .then(data => {
        const json = JSON.parse(data);
        console.log(json.data.price);
        document.getElementById('selectProductUpdate').value = json.data.customer_id;
        document.getElementById('inputProductNameUpdate').value = json.data.product_name;
        document.getElementById('inputUnitPriceUpdate').value = json.data.price;
        document.getElementById('inputQuantityUpdate').value = json.data.quantity;
        document.getElementById('paymentID').value = json.data.id;
    })
    .catch(err => {
        alert( "Error : "  + err.message );
    });

    const myModal = new bootstrap.Modal( document.getElementById('modalpaymentUpdate') );
    myModal.show();
}