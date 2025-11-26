// Load products onto homepage

fetch(API_BASE + "products/list.php")
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById('products-container');

    data.products.forEach(item => {
      container.innerHTML += `
        <div class="product-card" onclick="viewProduct(${item.id})">
            <img src="${item.image_url ?? 'assets/default-product.png'}" />
            <h4>${item.name}</h4>
            <p>GHS ${item.price}</p>
        </div>
      `;
    });
  });

function viewProduct(id) {
  window.location.href = "product.html?id=" + id;
}
