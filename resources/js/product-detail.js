var productDetail = 1;

function increaseQuantity() {
    productDetail++;
    document.getElementById("quantity").innerText = productDetail;
}

function decreaseQuantity() {
    if (productDetail > 1) {
        productDetail--;
        document.getElementById("quantity").innerText = productDetail;
    }

}