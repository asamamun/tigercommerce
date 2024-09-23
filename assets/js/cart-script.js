
    document.getElementById('cart-badge').innerText = cart.getCartCount();

    function addToCart(id, price, title, thumbnail){
        cart.addItem({id, price, title, thumbnail}, 1); 
        //sweet alert
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Item '+ title +' added to cart',
            showConfirmButton: false,
            timer: 1500
        })
        //c.addItemToCart(id, price, title, thumbnail);
        document.getElementById('cart-badge').innerText = cart.getCartCount();
    }