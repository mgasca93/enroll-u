document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementsByClassName('formCustomer');
    for( let i = 0; i < form.length; i++){
        form[i].addEventListener('submit',  e => {
            e.preventDefault();
            const data = Object.fromEntries( new FormData( e.target ) );
            addProduct( data, e.target.action, data._token );
        });
    }
});

function addProduct( data, ruta, token ){
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
        console.log(json.data.email);
        document.getElementById('inputNameUpdate').value = json.data.name;
        document.getElementById('inputLastnameUpdate').value = json.data.paternal_surname;
        document.getElementById('inputEmailUpdate').value = json.data.email;
        document.getElementById('customerId').value = json.data.id;
    })
    .catch(err => {
        alert( "Error : "  + err.message );
    });

    const myModal = new bootstrap.Modal( document.getElementById('modalUpdateCustomer') );
    myModal.show();
}