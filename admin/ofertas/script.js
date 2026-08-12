
// Função para favoritar produto
function toggleWishlist(button, productId) {
    const icon = button.querySelector('ion-icon');
    const isActive = button.classList.contains('active');
    
    // Alternar estado visual
    if (isActive) {
        button.classList.remove('active');
        icon.setAttribute('name', 'heart-outline');
    } else {
        button.classList.add('active');
        icon.setAttribute('name', 'heart');
    }
    
    // Chamada AJAX para atualizar no servidor
    fetch('wishlist.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: isActive ? 'remove' : 'add',
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar contador de favoritos se necessário
            const wishlistCount = document.getElementById('wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = data.count;
            }
        } else {
            // Reverter visualmente se falhar
            if (isActive) {
                button.classList.add('active');
                icon.setAttribute('name', 'heart');
            } else {
                button.classList.remove('active');
                icon.setAttribute('name', 'heart-outline');
            }
            alert('Erro: ' + (data.message || 'Não foi possível atualizar seus favoritos'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Reverter visualmente
        if (isActive) {
            button.classList.add('active');
            icon.setAttribute('name', 'heart');
        } else {
            button.classList.remove('active');
            icon.setAttribute('name', 'heart-outline');
        }
    });
}

// Função para visualizar detalhes do produto
function viewProductDetails(productId) {
    // Redirecionar para a página de detalhes
    window.location.href = `detalhes-produto.php?id=${productId}`;
    
    // Alternativa: Abrir modal
    // fetch(`get_product_details.php?id=${productId}`)
    // .then(response => response.json())
    // .then(data => {
    //     // Preencher e exibir modal com os detalhes
    //     openProductModal(data);
    // });
}

// Função para comparar produtos
function addToCompare(productId) {
    fetch('compare.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar contador de comparação
            const compareCount = document.getElementById('compare-count');
            if (compareCount) {
                compareCount.textContent = data.count;
            }
            
            // Mostrar feedback
            showNotification('Produto adicionado à comparação');
            
            // Alternar ícone visualmente
            const compareButtons = document.querySelectorAll(`[onclick="addToCompare(${productId})"]`);
            compareButtons.forEach(button => {
                button.classList.toggle('active');
                const icon = button.querySelector('ion-icon');
                icon.setAttribute('name', button.classList.contains('active') ? 'repeat' : 'repeat-outline');
            });
        } else {
            showNotification(data.message || 'Erro ao adicionar à comparação', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erro de conexão', 'error');
    });
}

// Função para adicionar ao carrinho
function addToCart(productId, quantity = 1) {
    fetch('carrinho.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'add',
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Atualizar contador do carrinho
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
            }
            
            // Feedback visual
            showNotification('Produto adicionado ao carrinho!');
            
            // Efeito visual no botão
            const cartButtons = document.querySelectorAll(`[onclick="addToCart(${productId})"]`);
            cartButtons.forEach(button => {
                button.classList.add('animate');
                setTimeout(() => {
                    button.classList.remove('animate');
                }, 1000);
            });
        } else {
            showNotification(data.message || 'Erro ao adicionar ao carrinho', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Erro de conexão', 'error');
    });
}

// Função auxiliar para mostrar notificações
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 500);
    }, 3000);
}

