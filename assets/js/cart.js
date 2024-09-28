class Cart {
    constructor() {
      this.items = [];
      this.loadCart();
    }
  
    loadCart() {
      const savedCart = localStorage.getItem('cart');
      if (savedCart) {
        this.items = JSON.parse(savedCart);
      }
    }
  
    saveCart() {
      localStorage.setItem('cart', JSON.stringify(this.items));
    }
  
    addItem(product, quantity = 1) {
      const existingItem = this.items.find(item => item.id === product.id);
      if (existingItem) {
        existingItem.quantity += quantity;
      } else {
        this.items.push({ ...product, quantity });
      }
      this.saveCart();
    }
  
    removeItem(productId) {
      this.items = this.items.filter(item => item.id !== productId);
      this.saveCart();
    }
  
    updateQuantity(productId, quantity) {
      const item = this.items.find(item => item.id === productId);
      if (item) {
        item.quantity = quantity;
        this.saveCart();
      }
    }
  
    getItems() {
      return this.items;
    }
    getCartCount() {
      return this.items.reduce((total, item) => total + parseInt(item.quantity), 0);
    }

  
    clearCart() {
      this.items = [];
      this.saveCart();
    }
  
    getTotalPrice() {
      return this.items.reduce((total, item) => total + item.price * item.quantity, 0);
    }
  }
  let cart = new Cart();
  function updateCartCount(){
    document.getElementById('cart-badge').innerText = cart.getCartCount();
  }
  //show cartcount in id cart-badge
  updateCartCount();
  
  /*
  // Usage example
  const cart = new Cart();
  
  // Fetch products from the API
  fetch('https://dummyjson.com/products')
    .then(response => response.json())
    .then(data => {
      // Assume we're adding the first product to the cart
      const product = data.products[0];
      cart.addItem(product, 2);
      console.log('Cart after adding item:', cart.getItems());
  
      // Update quantity
      cart.updateQuantity(product.id, 3);
      console.log('Cart after updating quantity:', cart.getItems());
  
      // Remove item
      cart.removeItem(product.id);
      console.log('Cart after removing item:', cart.getItems());
  
      // Get total price
      console.log('Total price:', cart.getTotalPrice());
    })
    .catch(error => console.error('Error fetching products:', error));

    */