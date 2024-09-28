


function displayCart() {
const cartItemsElement = document.getElementById('cartItems');
const grandTotalElement = document.getElementById('grandTotal');
cartItemsElement.innerHTML = '';

cart.getItems().forEach(item => {
    console.log(item);
const row = document.createElement('tr');
const price = parseFloat(item.price);
row.innerHTML = `
    <td>${item.id}</td>
    <td>${item.title}</td>
    <td>$${price.toFixed(2)}</td>
    <td>
        <input type="number" min="1" value="${item.quantity}" 
               class="form-control quantity-input" onchange="updateQuantity(${item.id}, this.value)">
    </td>
    <td>$${(price * item.quantity).toFixed(2)}</td>
    <td>
        <button class="btn btn-danger btn-sm delete-item" onclick="deleteItem(${item.id})">Delete</button>
    </td>
`;
cartItemsElement.appendChild(row);
});




grandTotalElement.textContent = `$${cart.getTotalPrice().toFixed(2)}`;

// Add event listeners for quantity changes and delete buttons
// addEventListeners();
}

//delete item

function deleteItem(id) {
cart.removeItem(id);
displayCart();
updateCartCount();
}

function updateQuantity(id, newQuantity) {
cart.updateQuantity(id, newQuantity);
displayCart();
updateCartCount();
}

/* function addEventListeners() {
document.querySelectorAll('.quantity-input').forEach(input => {
input.addEventListener('change', (e) => {
    const id = parseInt(e.target.getAttribute('data-id'));
    const newQuantity = parseInt(e.target.value);
    cart.updateQuantity(id, newQuantity);
    displayCart();
});
});
} */
/* document.querySelectorAll('.delete-item').forEach(button => {
button.addEventListener('click', (e) => {
    const id = parseInt(e.target.getAttribute('data-id'));
    cart.removeItem(id);
    displayCart();
});
}); */




displayCart();

