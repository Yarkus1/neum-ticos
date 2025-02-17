
  class CartManager {
    constructor() {
      this.cart = JSON.parse(localStorage.getItem('cart')) || [];
      this.updateStorage();
    }
  
    addToCart(product) {
      this.cart.push(product);
      this.updateStorage();
    }
  
    updateStorage() {
      localStorage.setItem('cart', JSON.stringify(this.cart));
      document.getElementById('cartCounter').textContent = `Carrito (${this.cart.length})`;
    }
  }
  

// Cargar productos desde la API
async function loadProducts(searchTerm = '') {
  try {
      const response = await fetch(`/api/products?search=${encodeURIComponent(searchTerm)}`);
      const products = await response.json();
      renderProducts(products);
  } 
  catch (error) {
      console.error('Error loading products:', error);
      renderProducts([]);

  }
}

function renderProducts(products) {
  const container = document.getElementById('productContainer');
  container.innerHTML = products.map(product => `
      <div class="product-card">
          <h3>${product.name}</h3> 
          <p>Marca: ${product.brand}</p>
          <p>Dimensiones: ${product.dimensions}</p>
          <p>Descripción: ${product.description}</p>
          <p>Stock: ${product.stock}</p>
          <p>Categoria: ${product.category}</p>
          <p>Material: ${product.material}</p>
          <p>Color: ${product.color}</p>
          <p>Tipo: ${product.type}</p>
          <p>Calidad: ${product.quality}</p>
          <p>Garantía: ${product.warranty}</p>
          <p>Precio: $${product.price}</p>
          <button onclick="cart.addToCart(${JSON.stringify(product)})">Añadir al carrito</button>
          <img src="${product.image}" alt="${product.name}">

      </div>
  `).join('');
}

// Búsqueda en tiempo real con debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', (e) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => loadProducts(e.target.value), 300);
});

const cart = new CartManager();
loadProducts();

document.addEventListener('DOMContentLoaded', () => {
  loadProducts();
  document.getElementById('searchInput').addEventListener('input', handleSearchInput);
});

function handleSearchInput(event) {
  const searchTerm = event.target.value;
  loadProducts(searchTerm);
}

  