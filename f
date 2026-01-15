function toggleWishlist(button, productId) {
    const formData = new FormData();
    formData.append('product_id', productId);

    fetch('save_favorite.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'added') {
            button.classList.add('heart-active');
            // เพิ่ม Effect หรือ Toast แจ้งเตือนเบาๆ
        } else if (data.status === 'removed') {
            button.classList.remove('heart-active');
        } else if (data.status === 'error') {
            alert(data.message); // เช่น "กรุณาล็อกอินก่อน"
        }
    })
    .catch(error => console.error('Error:', error));
}
