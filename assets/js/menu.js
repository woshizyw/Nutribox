let cart = [];

document.addEventListener('DOMContentLoaded', function () {
    // 监听添加到购物车按钮的点击事件
    document.querySelectorAll('.add-to-cart').forEach(function (button) {
        button.addEventListener('click', function () {
            const mealkitId = this.getAttribute('data-id');
            const mealkitName = this.getAttribute('data-name');
            const mealkitPrice = parseFloat(this.getAttribute('data-price'));
            const mealkitImage = this.closest('.card').querySelector('img').src; // 获取图片路径
    
            const existingItem = cart.find(item => item.id === mealkitId);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ id: mealkitId, name: mealkitName, price: mealkitPrice, image: mealkitImage, quantity: 1 });
            }
    
            updateCartDisplay();
        });
    });

    document.getElementById('open-cart').addEventListener('click', function () {
        const cartModal = new bootstrap.Modal(document.getElementById('cart-modal'));
        cartModal.show();
    });

    document.getElementById('clear-cart').addEventListener('click', function () {
        cart = [];
        updateCartDisplay();
    });

    document.getElementById('checkout-btn').addEventListener('click', function () {
        const mealkitId = 1;
        const quantity = 2;
        const totalPrice = 25.00;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '/nutribox/pages/cart_session.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                window.location.href = '/nutribox/pages/checkout.php';
            }
        };
        xhr.send('mealkit_id=' + mealkitId + '&quantity=' + quantity + '&total_price=' + totalPrice);
    });

    function updateCartDisplay() {
        const cartItemsContainer = document.getElementById('cart-items');
        cartItemsContainer.innerHTML = '';
        let total = 0;
    
        cart.forEach(function (item, index) {
            total += item.price * item.quantity;
            const li = document.createElement('li');
            li.classList.add('list-group-item', 'd-flex', 'flex-column', 'justify-content-between', 'align-items-start');
    
            // 构建购物车中的每个mealkit
            li.innerHTML = `
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <img src="${item.image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                    <div class="mealkit-info">
                        <div>${item.name} - $${(item.price * item.quantity).toFixed(2)} x ${item.quantity}</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-danger btn-sm decrement-item" data-index="${index}">-</button>
                        <button class="btn btn-success btn-sm increment-item" data-index="${index}">+</button>
                        <!-- 下拉箭头按钮 -->
                        <button class="btn btn-info btn-sm toggle-details" data-index="${index}" style="margin-left: 5px;">&#9660;</button>
                    </div>
                </div>
                <!-- 显示选中的额外side dishes在上方 -->
                <div class="extra-items" id="extra-items-${index}" style="margin-top: 10px;">
                    <!-- 动态显示用户已选择的side dishes -->
                    ${generateSelectedExtras(item.extras, index)}
                </div>
                <!-- 隐藏的详细信息区域，展开后显示 -->
                <div class="item-details" id="details-${index}" style="display: none; margin-top: 10px;">
                    <h5>Customize your Meal</h5>
                    ${generateCustomOptions(index)}
                </div>
            `;
            cartItemsContainer.appendChild(li);
        });
    
        document.getElementById('cart-total').textContent = total.toFixed(2);
    
        // 增加或减少数量的按钮事件
        document.querySelectorAll('.increment-item').forEach(button => {
            button.addEventListener('click', function () {
                const index = this.getAttribute('data-index');
                cart[index].quantity += 1;
                updateCartDisplay();
            });
        });
    
        document.querySelectorAll('.decrement-item').forEach(button => {
            button.addEventListener('click', function () {
                const index = this.getAttribute('data-index');
                if (cart[index].quantity > 1) {
                    cart[index].quantity -= 1;
                } else {
                    cart.splice(index, 1);
                }
                updateCartDisplay();
            });
        });
    
        // 展开或隐藏详细信息的按钮事件
        document.querySelectorAll('.toggle-details').forEach(button => {
            button.addEventListener('click', function () {
                const index = this.getAttribute('data-index');
                const detailsDiv = document.getElementById(`details-${index}`);
                detailsDiv.style.display = detailsDiv.style.display === 'none' ? 'block' : 'none';
            });
        });
    
        // 删除额外食物的按钮事件
        document.querySelectorAll('.remove-extra').forEach(button => {
            button.addEventListener('click', function () {
                const itemIndex = this.getAttribute('data-item-index');
                const extraIndex = this.getAttribute('data-extra-index');
    
                const extraPrice = cart[itemIndex].extras[extraIndex].price;
                cart[itemIndex].price -= extraPrice;  // 减去删除的额外食物的价格
                cart[itemIndex].extras.splice(extraIndex, 1);  // 从extras数组中移除
    
                updateCartDisplay();  // 更新显示
            });
        });
    }
    
    // 生成选中的额外食物信息，显示在mealkit上方
    function generateSelectedExtras(extras, itemIndex) {
        if (!extras || extras.length === 0) {
            return '';
        }
    
        return extras.map((extra, extraIndex) => `
            <div class="extra-item d-flex justify-content-between align-items-center" style="background-color: #e7f3e7; padding: 5px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 5px;">
                ${extra.name} (+$${extra.price.toFixed(2)}) 
                <button class="btn btn-danger btn-sm remove-extra" data-item-index="${itemIndex}" data-extra-index="${extraIndex}">Remove</button>
            </div>
        `).join('');
    }
    
    function generateCustomOptions(index) {
        const customOptions = [
            { name: 'Cola', price: 3 },
            { name: 'Fries', price: 3 },
            { name: 'Cheese', price: 2.5 },
            { name: 'Gluten allergy', price: 0 },
            { name: 'Seafood allergy', price: 0 },
            { name: 'Peanut Allergy', price: 0 }
        ];
    
        let optionsHtml = '';
        customOptions.forEach((option, i) => {
            optionsHtml += `
                <div class="custom-option d-flex justify-content-between align-items-center">
                    <span>${option.name} (+$${option.price.toFixed(2)})</span>
                    <button class="btn btn-secondary btn-sm add-option" data-index="${index}" data-option-index="${i}" data-price="${option.price}">
                        +
                    </button>
                </div>
            `;
        });
    
        return optionsHtml;
    }
    

    // 处理添加额外side dish事件
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('add-option')) {
            const itemIndex = event.target.getAttribute('data-index');
            const optionIndex = event.target.getAttribute('data-option-index');
            let optionName = event.target.parentElement.querySelector('span').textContent;
            const optionPrice = parseFloat(event.target.getAttribute('data-price'));
    
            // 使用正则表达式移除optionName中的价格信息，确保只有食物名称
            optionName = optionName.replace(/\(\+\$[0-9,.]+\)/, '').trim();
    
            // 确保每个item只会添加一次extras中的信息
            if (!cart[itemIndex].extras) {
                cart[itemIndex].extras = [];
            }
    
            // 检查是否已经存在相同的额外食物，以防止重复添加
            const existingExtra = cart[itemIndex].extras.find(extra => extra.name === optionName);
            if (!existingExtra) {
                cart[itemIndex].extras.push({ name: optionName, price: optionPrice });
                cart[itemIndex].price += optionPrice;  // 确保只添加一次价格
            }
    
            updateCartDisplay();  // 更新购物车显示
        }
    });
    

     






    

    // Sort buttons event listener
    const sortButtons = document.querySelectorAll('.sort-button');
    sortButtons.forEach(button => {
        button.addEventListener('click', function () {
            const sortOption = this.getAttribute('data-sort');
            applyFilters(getSelectedTags(), sortOption);
        });
    });

    const tagButtons = document.querySelectorAll('.tag-button');
    tagButtons.forEach(button => {
        button.addEventListener('click', function () {
            this.classList.toggle('active');
            applyFilters(getSelectedTags(), getSortOption());
        });
    });

    function getSelectedTags() {
        let tags = [];
        document.querySelectorAll('.tag-button.active').forEach(button => {
            tags.push(button.getAttribute('data-tag-id'));
        });
        return tags;
    }

    function getSortOption() {
        return document.querySelector('.sort-button.active')?.getAttribute('data-sort') || 'price_asc';
    }

    function applyFilters(tags, sort) {
        fetch('/NutriBox/pages/menu_filter.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `tags=${JSON.stringify(tags)}&sort=${sort}`
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('mealkit-grid').innerHTML = data;
        })
        .catch(error => console.error('Error:', error));
    }

    document.getElementById('add-custom-tag').addEventListener('click', function () {
        const customTagInput = document.getElementById('custom-tag-input');
        if (!customTagInput || customTagInput.value.trim() === '') {
            alert('Please enter a tag');
            return;
        }
        const newTag = customTagInput.value.trim();

        const existingTags = Array.from(document.querySelectorAll('.tag-button')).map(button => button.textContent.trim().toLowerCase());
        if (existingTags.includes(newTag.toLowerCase())) {
            alert('This tag already exists');
            return;
        }

        fetch('/NutriBox/pages/add_custom_tag.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `tag=${encodeURIComponent(newTag)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tagList = document.getElementById('tags-list');
                const addButton = document.getElementById('add-custom-tag').parentElement;

                const li = document.createElement('li');
                li.classList.add('list-group-item');

                const button = document.createElement('button');
                button.classList.add('btn', 'btn-secondary', 'tag-button');
                button.setAttribute('data-tag-id', data.tag_id);
                button.textContent = newTag;

                button.addEventListener('click', function () {
                    this.classList.toggle('active');
                    applyFilters(getSelectedTags(), getSortOption());
                });

                li.appendChild(button);
                tagList.insertBefore(li, addButton);

                button.classList.remove('active');
                customTagInput.value = '';
            } else {
                alert(data.message || 'Error adding tag');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    const cartPanel = document.getElementById('cart-panel');
    const openCartBtn = document.getElementById('open-cart');
    const closeCartBtn = document.getElementById('close-cart');

    // 打开购物车侧边栏
    openCartBtn.addEventListener('click', function () {
        cartPanel.classList.add('open');
    });

    // 关闭购物车侧边栏
    closeCartBtn.addEventListener('click', function () {
        cartPanel.classList.remove('open');
    });
});





document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const cartButton = document.getElementById('cart-button');
        const card = this.closest('.card');
        const img = card.querySelector('img');  // 获取卡片图片
        const imgClone = img.cloneNode();  // 克隆图片

        // 获取图片的初始位置（相对于页面）
        const imgRect = img.getBoundingClientRect();
        // 获取购物车按钮的位置（相对于视口/窗口）
        const cartRect = cartButton.getBoundingClientRect();

        // 设置克隆图片的初始样式
        imgClone.style.position = 'absolute';
        imgClone.style.width = '100px';  // 克隆图片缩小尺寸
        imgClone.style.height = '100px';
        imgClone.style.zIndex = '9999';  // 确保克隆图片在最前
        imgClone.style.top = `${imgRect.top + window.scrollY}px`;  // 添加页面滚动偏移
        imgClone.style.left = `${imgRect.left + window.scrollX}px`;

        // 将克隆图片添加到页面
        document.body.appendChild(imgClone);

        // 计算图片飞行的目标位置（购物车按钮）
        const targetX = cartRect.left + window.scrollX;  // 相对于页面的购物车横坐标
        const targetY = cartRect.top + window.scrollY;   // 相对于页面的购物车纵坐标

        // 动画: 克隆图片飞向购物车
        imgClone.animate([
            { transform: 'translate(0, 0)' },  // 初始位置
            {
                transform: `translate(${targetX - imgRect.left}px, ${targetY - imgRect.top}px)`,
                opacity: 0.5
            }  // 终点位置
        ], {
            duration: 300,
            easing: 'ease-in-out',
            fill: 'forwards'
        });

        // 动画结束后移除克隆图片
        setTimeout(() => {
            imgClone.remove();
        }, 300);

        // 更新购物车逻辑...
    });
});





document.addEventListener('DOMContentLoaded', function () {
    const cartPanel = document.getElementById('cart-panel');
    const openCartBtn = document.getElementById('open-cart');
    const closeCartBtn = document.getElementById('close-cart'); // 获取关闭按钮

    // 打开购物车侧边栏
    openCartBtn.addEventListener('click', function () {
        cartPanel.classList.add('open');
    });

    // 关闭购物车侧边栏
    closeCartBtn.addEventListener('click', function () {
        cartPanel.classList.remove('open');
    });
});






