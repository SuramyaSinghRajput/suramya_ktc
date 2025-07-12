<?php $__env->startSection('product', 'active menu-item-open'); ?>
<?php $__env->startSection('content'); ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Product List</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Segoe UI', sans-serif;
                background-color: #f1f7fc;
                margin: 0;
                padding: 20px;
            }

            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .header h1 {
                color: #1d3557;
                font-size: 28px;
                display: flex;
                align-items: center;
            }

            .header h1::before {
                content: '🌿';
                margin-right: 10px;
                font-size: 26px;
            }

            .btn-add {
                background-color: #1d3557;
                color: white;
                padding: 10px 18px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
            }

            .search-filter {
                display: flex;
                gap: 10px;
                margin-bottom: 20px;
            }

            .search-filter input[type="text"] {
                flex: 1;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 8px;
                font-size: 14px;
            }

            .search-filter button {
                padding: 10px 16px;
                border: 1px solid #ccc;
                background-color: white;
                border-radius: 8px;
                cursor: pointer;
            }

            .subtitle {
                font-size: 12px;
                color: #6c757d;
                margin-top: -10px;
                margin-bottom: 20px;
            }

            .card {
                background: linear-gradient(to right, #2a9d8f, #457b9d);
                padding: 14px;
                color: white;
                font-weight: bold;
                border-radius: 10px 10px 0 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background-color: white;
                border-radius: 0 0 10px 10px;
                overflow: hidden;
            }

            th,
            td {
                padding: 14px;
                text-align: left;
                border-bottom: 1px solid #eee;
            }

            th {
                background-color: #f8f9fa;
                font-weight: bold;
            }

            td.status {
                text-align: center;
            }

            .badge {
                background-color: #d1f4e2;
                color: #1b9c72;
                padding: 5px 10px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: 500;
                display: inline-block;
            }

            .load-more-container {
                text-align: center;
                margin-top: 20px;
            }

            #load-more {
                padding: 10px 20px;
                background-color: #1d3557;
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
            }
        </style>
    </head>

    <body>

        <div class="header">
            <h1>Product List</h1>
            <a href="<?php echo e(url('/admin/product/add')); ?>" class="btn-add">+ Add New Product</a>
        </div>

        <div class="search-filter">
            <input type="text" id="search-box" placeholder="Search products..." />
            <button>🔽 Filter</button>
        </div>
        <div class="subtitle">Fuzzy search enabled - spelling mistakes allowed</div>

        <div class="card">Medicinal Plant Products</div>
        <table>
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>SKU</th>
                    <th>Item Name</th>
                    <th>Stock in Hand</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="product-tbody">
                <!-- AJAX-loaded data will appear here -->
            </tbody>
        </table>

        <div class="load-more-container">
            <button id="load-more">Load More</button>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            const baseUrl = "<?php echo e(url('/')); ?>";
        </script>
        <script>
            let page = 1;
            let serial = 1;
            let search = "";

            function loadProducts(reset = false) {
                $.ajax({
                    url: "<?php echo e(route('admin.product.index')); ?>",
                    method: "GET",
                    data: {
                        page: page,
                        search: search
                    },
                    success: function(res) {
                        if (reset) {
                            $('#product-tbody').html('');
                            serial = 1;
                            $('#load-more').show();
                        }

                        if (res.data.length === 0) {
                            $('#load-more').hide();
                            return;
                        }

                        let html = '';
                        res.data.forEach((item, index) => {
                        html += `
                            <tr onclick="window.location='${baseUrl}/admin/product/lot/list/${item.id}'" style="cursor:pointer;">
                            <td>${serial++}</td>
                            <td>${item.sku}</td>
                            <td>${item.full_name}</td>
                            <td>${item.stock_quantity ?? 'N/A'} kg</td>
                            <td>
                                <a href="${baseUrl}/admin/product/edit/${item.id}" title="Edit" style="margin-right:10px; color:#007bff;" onclick="event.stopPropagation();">
                                <i class="fas fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="event.stopPropagation(); deleteProduct(${item.id});" title="Delete" style="color:#dc3545;">
                                <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                            </tr>
                        `;
                        });


                        $('#product-tbody').append(html);

                        if (!res.next_page_url) {
                            $('#load-more').hide();
                        }
                    },
                    error: function() {
                        alert('Failed to load data');
                    }
                });
            }

            $('#load-more').on('click', function() {
                page++;
                loadProducts();
            });

            $('#search-box').on('input', function() {
                search = $(this).val();
                page = 1;
                loadProducts(true);
            });

            // Initial load
            $(document).ready(function() {
                loadProducts();
            });
        </script>

        <script>
            function deleteproduct(id) {
                const password = prompt('Please enter your password to confirm deletion:');

                if (password === null || password === '') {
                    alert('Deletion cancelled or password not entered.');
                    return;
                }

                $.ajax({
                    url: `${baseUrl}/admin/product/delete/${id}`,
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        _method: 'DELETE',
                        password: password
                    },
                    success: function(response) {
                        alert(response.message || 'Product deleted successfully!');
                        page = 1;
                        loadproduct(true);
                    },
                    error: function(xhr) {
                        if (xhr.status === 403 || xhr.status === 422) {
                            alert(xhr.responseJSON.message || 'Password incorrect!');
                        } else {
                            alert('Delete failed!');
                        }
                    }
                });
            }
        </script>
    </body>

    </html>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\ktc\resources\views/admin/pages/product/list.blade.php ENDPATH**/ ?>