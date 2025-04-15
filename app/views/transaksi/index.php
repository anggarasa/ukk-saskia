<?php require_once '../app/views/layouts/header.php'?>

<!-- Main Content -->
<main class="flex-grow container mx-auto px-4 py-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Transaksi Baru</h2>
        <p class="text-gray-600">Isi detail transaksi pelanggan</p>
    </div>

    <!-- Transaction Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Customer & Product Selection -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Selection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-user mr-2 text-indigo-500"></i>
                    Pilih Pelanggan
                </h3>
                <div class="relative">
                    <select id="customer" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                        <option value="" disabled selected>Pilih pelanggan</option>
                        <?php foreach ($data['pelanggans'] as $pelanggan): ?>
                        <option value="<?= $pelanggan['id'] ?>"><?= $pelanggan['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Product Selection -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-shopping-cart mr-2 text-indigo-500"></i>
                    Pilih Produk
                </h3>
                <div class="relative mb-4">
                    <div class="flex space-x-2">
                        <select id="product" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                            <option value="" disabled selected>Pilih produk</option>
                            <?php foreach ($data['produks'] as $produk): ?>
                            <option value="<?= $produk['id'] ?>" data-price="<?= $produk['harga_produk'] ?>" data-stock="<?= $produk['stok'] ?>"><?= $produk['nama_produk'] ?> - Rp <?= number_format($produk['harga_produk'], 0, ',', '.') ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button id="add-product" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Product List -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-3 text-left">Produk</th>
                            <th class="py-3 px-3 text-center">Harga</th>
                            <th class="py-3 px-3 text-center">Jumlah</th>
                            <th class="py-3 px-3 text-center">Subtotal</th>
                            <th class="py-3 px-3 text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody id="product-list" class="text-gray-600">
                        <!-- Products will be added here by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div id="empty-cart-message" class="text-center py-8 text-gray-500">
                    <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                    <p>Belum ada produk dipilih</p>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-file-invoice-dollar mr-2 text-indigo-500"></i>
                    Ringkasan Pembayaran
                </h3>

                <div class="space-y-4">
                    <div class="flex justify-between border-b pb-3">
                        <span class="text-gray-600">Subtotal</span>
                        <span id="subtotal" class="font-semibold">Rp 0</span>
                    </div>

                    <div class="flex justify-between border-b pb-3">
                        <span class="text-gray-600">Pajak (10%)</span>
                        <span id="tax" class="font-semibold">Rp 0</span>
                    </div>

                    <div class="flex justify-between text-lg">
                        <span class="font-bold text-gray-800">Total</span>
                        <span id="total" class="font-bold text-indigo-600">Rp 0</span>
                    </div>

                    <div class="mt-6">
                        <label class="block text-gray-700 mb-2" for="payment">
                            Uang yang Diberikan
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">Rp</span>
                            </div>
                            <input type="number" id="payment" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="0">
                        </div>
                    </div>

                    <div class="flex justify-between text-lg text-green-600">
                        <span class="font-bold">Kembalian</span>
                        <span id="change" class="font-bold">Rp 0</span>
                    </div>

                    <button id="submit-transaction" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition mt-4 disabled:bg-gray-400 disabled:cursor-not-allowed">
                        <i class="fas fa-check-circle mr-2"></i>
                        Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Global variables
        const cart = [];
        let selectedCustomer = null;

        // DOM elements
        const customerSelect = document.getElementById('customer');
        const productSelect = document.getElementById('product');
        const addProductBtn = document.getElementById('add-product');
        const productList = document.getElementById('product-list');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const subtotalElement = document.getElementById('subtotal');
        const taxElement = document.getElementById('tax');
        const totalElement = document.getElementById('total');
        const paymentInput = document.getElementById('payment');
        const changeElement = document.getElementById('change');
        const submitBtn = document.getElementById('submit-transaction');

        // Initialize
        updateSummary();
        toggleEmptyCartMessage();

        // Event listeners
        customerSelect.addEventListener('change', function() {
            selectedCustomer = this.value;
            validateForm();
        });

        addProductBtn.addEventListener('click', addProductToCart);

        paymentInput.addEventListener('input', function() {
            calculateChange();
            validateForm();
        });

        submitBtn.addEventListener('click', submitTransaction);

        // Functions
        function addProductToCart() {
            const productId = productSelect.value;
            if (!productId) {
                showAlert('Pilih produk terlebih dahulu', 'error');
                return;
            }

            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const productName = selectedOption.text.split(' - ')[0];
            const price = parseFloat(selectedOption.dataset.price);
            const stock = parseInt(selectedOption.dataset.stock);

            // Check if product already in cart
            const existingProduct = cart.find(item => item.id === productId);

            if (existingProduct) {
                // Check stock
                if (existingProduct.quantity >= stock) {
                    showAlert(`Stok produk ${productName} tidak mencukupi`, 'error');
                    return;
                }

                existingProduct.quantity++;
                existingProduct.subtotal = existingProduct.quantity * existingProduct.price;
                updateProductRow(existingProduct);
            } else {
                // Check stock
                if (stock <= 0) {
                    showAlert(`Stok produk ${productName} tidak mencukupi`, 'error');
                    return;
                }

                // Add new product to cart
                const newProduct = {
                    id: productId,
                    name: productName,
                    price: price,
                    quantity: 1,
                    subtotal: price
                };

                cart.push(newProduct);
                addProductRow(newProduct);
            }

            updateSummary();
            toggleEmptyCartMessage();
            calculateChange();
            validateForm();
        }

        function addProductRow(product) {
            const row = document.createElement('tr');
            row.id = `product-${product.id}`;
            row.innerHTML = `
            <td class="py-3 px-3">${product.name}</td>
            <td class="py-3 px-3 text-center">Rp ${formatNumber(product.price)}</td>
            <td class="py-3 px-3 text-center">
                <div class="flex items-center justify-center">
                    <button class="decrement-btn bg-gray-200 px-2 rounded-l">-</button>
                    <input type="number" min="1" value="${product.quantity}" class="quantity-input w-16 text-center border-t border-b border-gray-300" readonly>
                    <button class="increment-btn bg-gray-200 px-2 rounded-r">+</button>
                </div>
            </td>
            <td class="py-3 px-3 text-center">Rp ${formatNumber(product.subtotal)}</td>
            <td class="py-3 px-3 text-center">
                <button class="remove-btn text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

            productList.appendChild(row);

            // Add event listeners to buttons
            const incrementBtn = row.querySelector('.increment-btn');
            const decrementBtn = row.querySelector('.decrement-btn');
            const removeBtn = row.querySelector('.remove-btn');

            incrementBtn.addEventListener('click', () => {
                incrementQuantity(product.id);
            });

            decrementBtn.addEventListener('click', () => {
                decrementQuantity(product.id);
            });

            removeBtn.addEventListener('click', () => {
                removeProduct(product.id);
            });
        }

        function updateProductRow(product) {
            const row = document.getElementById(`product-${product.id}`);
            if (row) {
                row.querySelector('.quantity-input').value = product.quantity;
                row.querySelectorAll('td')[3].textContent = `Rp ${formatNumber(product.subtotal)}`;
            }
        }

        function incrementQuantity(productId) {
            const product = cart.find(item => item.id === productId);
            if (product) {
                // Check stock
                const selectedOption = Array.from(productSelect.options).find(option => option.value === productId);
                const stock = parseInt(selectedOption.dataset.stock);

                if (product.quantity >= stock) {
                    showAlert(`Stok produk ${product.name} tidak mencukupi`, 'error');
                    return;
                }

                product.quantity++;
                product.subtotal = product.quantity * product.price;
                updateProductRow(product);
                updateSummary();
                calculateChange();
            }
        }

        function decrementQuantity(productId) {
            const product = cart.find(item => item.id === productId);
            if (product && product.quantity > 1) {
                product.quantity--;
                product.subtotal = product.quantity * product.price;
                updateProductRow(product);
                updateSummary();
                calculateChange();
            }
        }

        function removeProduct(productId) {
            const index = cart.findIndex(item => item.id === productId);
            if (index !== -1) {
                cart.splice(index, 1);
                const row = document.getElementById(`product-${productId}`);
                if (row) {
                    row.remove();
                }
                updateSummary();
                toggleEmptyCartMessage();
                calculateChange();
                validateForm();
            }
        }

        function updateSummary() {
            const subtotal = cart.reduce((total, item) => total + item.subtotal, 0);
            const tax = subtotal * 0.1; // 10% tax
            const total = subtotal + tax;

            subtotalElement.textContent = `Rp ${formatNumber(subtotal)}`;
            taxElement.textContent = `Rp ${formatNumber(tax)}`;
            totalElement.textContent = `Rp ${formatNumber(total)}`;
        }

        function calculateChange() {
            const totalAmount = cart.reduce((total, item) => total + item.subtotal, 0) * 1.1; // Including tax
            const payment = parseFloat(paymentInput.value) || 0;
            const change = payment - totalAmount;

            changeElement.textContent = `Rp ${formatNumber(Math.max(0, change))}`;

            // Update class based on whether payment is sufficient
            if (change >= 0 && payment > 0) {
                changeElement.classList.remove('text-red-600');
                changeElement.classList.add('text-green-600');
            } else {
                changeElement.classList.remove('text-green-600');
                changeElement.classList.add('text-red-600');
            }
        }

        function toggleEmptyCartMessage() {
            if (cart.length === 0) {
                emptyCartMessage.classList.remove('hidden');
                if (productList.classList.contains('hidden')) {
                    productList.classList.remove('hidden');
                }
            } else {
                emptyCartMessage.classList.add('hidden');
            }
        }

        function validateForm() {
            const totalAmount = cart.reduce((total, item) => total + item.subtotal, 0) * 1.1;
            const payment = parseFloat(paymentInput.value) || 0;

            // Validate all required fields
            const isValid =
                selectedCustomer &&
                cart.length > 0 &&
                payment >= totalAmount;

            submitBtn.disabled = !isValid;
        }

        function submitTransaction() {
            if (submitBtn.disabled) return;

            const totalAmount = cart.reduce((total, item) => total + item.subtotal, 0) * 1.1;
            const payment = parseFloat(paymentInput.value);

            // Prepare transaction data
            const transactionData = {
                pelanggan_id: selectedCustomer,
                items: cart.map(item => ({
                    produk_id: item.id,
                    quantity: item.quantity,
                    subtotal: item.subtotal
                })),
                total_harga: totalAmount,
                uang_diberikan: payment
            };

            // Send transaction to server
            fetch('/ukk-jeni/public/transaksi/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(transactionData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showAlert('Transaksi berhasil disimpan', 'success');
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Terjadi kesalahan saat memproses transaksi', 'error');
                });
        }

        // Utility functions
        function formatNumber(number) {
            return number.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function showAlert(message, type) {
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `fixed top-4 right-4 px-6 py-3 rounded-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} z-50`;
            alert.textContent = message;

            // Add alert to body
            document.body.appendChild(alert);

            // Remove alert after 3 seconds
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    });
</script>
<?php require_once '../app/views/layouts/footer.php'?>
