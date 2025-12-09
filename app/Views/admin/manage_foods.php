<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý món ăn</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
    body {
        background-color: #f5f6fa;
        font-family: 'Roboto', sans-serif;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: #2c3e50;
        padding-top: 20px;
    }

    .sidebar .nav-link {
        color: #ecf0f1;
        font-size: 15px;
        padding: 12px 20px;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
        background: #34495e;
        border-radius: 4px;
    }

    .admin-header {
        height: 60px;
        background: white;
        padding: 10px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-left: 250px;
        position: fixed;
        width: calc(100% - 250px);
        border-bottom: 1px solid #ddd;
    }

    .content {
        margin-left: 250px;
        padding: 90px 30px;
    }

    .preview-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .card.fixed-height {
        height: 800px;
        display: flex;
        flex-direction: column;
    }

    .table-container {
        flex: 1;
    }

    .pagination-wrapper {
        height: 60px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 15px;
        border-top: 1px solid #eee;
    }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4 class="text-center text-light mb-4">
            <i class="fa-solid fa-circle-user"></i> <?= session('username') ?>
        </h4>

        <nav class="nav flex-column">
            <a class="nav-link" href="/admin/manage_accounts"><i class="fa-solid fa-users"></i> Quản lý tài khoản</a>
            <a class="nav-link" href="/admin/employees"><i class="fa-solid fa-user-tie"></i> Quản lý nhân sự</a>
            <a class="nav-link" href="/admin/customers"><i class="fa-solid fa-user-group"></i> Quản lý khách hàng</a>
            <a class="nav-link" href="/admin/categories"><i class="fa-solid fa-layer-group"></i> Quản lý danh mục</a>
            <a class="nav-link active" href="/admin/foods"><i class="fa-solid fa-bowl-food"></i> Quản lý món ăn</a>
            <a class="nav-link" href="/admin/orders"><i class="fa-solid fa-receipt"></i> Quản lý đơn hàng</a>
            <a class="nav-link" href="/admin/promotions"><i class="fa-solid fa-ticket"></i> Quản lý khuyến mãi</a>
            <a class="nav-link" href="/admin/feedbacks"><i class="fa-solid fa-comments"></i> Đánh giá & phản hồi</a>
            <a class="nav-link" href="/admin/reports"><i class="fa-solid fa-chart-line"></i> Báo cáo thống kê</a>

            <hr class="text-light">
            <a class="nav-link text-danger" href="/logout">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </a>
        </nav>
    </div>

    <!-- HEADER -->
    <header class="admin-header">
        <span><i class="fa-solid fa-user"></i> <?= session('username') ?></span>
    </header>

    <!-- CONTENT -->
    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Quản lý món ăn</h2>

            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fa fa-plus"></i> Thêm món ăn
            </button>
        </div>

        <div class="card shadow-sm fixed-height">
            <div class="card-body table-container">

                <div id="ajaxAlert"></div>

                <!-- TÌM KIẾM -->
                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <input type="text" id="filter_search" class="form-control w-50 ms-auto"
                            placeholder="Tìm kiếm món ăn...">
                    </div>
                </div>

                <!-- BẢNG MÓN ĂN -->
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên món</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th width="130px">Hành động</th>
                        </tr>
                    </thead>

                    <tbody id="foodTableBody">
                        <?php if (!empty($foods)): ?>
                        <?php foreach ($foods as $f): ?>
                        <tr>
                            <td><?= $f['food_id'] ?></td>

                            <td>
                                <img src="<?= base_url('upload/foods/' . $f['image_url']); ?>" class="preview-img">
                            </td>

                            <td><?= esc($f['name']) ?></td>
                            <td><?= esc($f['category_name']) ?></td>

                            <td><?= number_format($f['price'], 0, ',', '.') ?> đ</td>

                            <td><?= $f['stock_quantity'] ?></td>

                            <td>
                                <button class="btn btn-warning btn-sm" onclick="openEditModal(
                                                <?= $f['food_id'] ?>,
                                                '<?= esc($f['name'], 'js') ?>',
                                                '<?= esc($f['description'], 'js') ?>',
                                                <?= $f['category_id'] ?>,
                                                '<?= intval($f['price']) ?>',
                                                '<?= $f['stock_quantity'] ?>',
                                                '<?= $f['image_url'] ?>'
                                            )">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-danger btn-sm" onclick="deleteFood(<?= $f['food_id'] ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-danger text-center fw-bold">
                                Không có món ăn nào
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>

                </table>

                <!-- PHÂN TRANG -->
                <div class="pagination-wrapper">
                    <?php if ($totalPages > 0): ?>
                    <div class="pagination d-flex">

                        <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-outline-primary me-1">&lt;</a>
                        <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-outline-primary me-1">
                            <?= $currentPage - 1 ?>
                        </a>
                        <?php endif; ?>

                        <span class="btn btn-primary me-1"><?= $currentPage ?></span>

                        <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" class="btn btn-outline-primary me-1">
                            <?= $currentPage + 1 ?>
                        </a>
                        <a href="?page=<?= $currentPage + 1 ?>" class="btn btn-outline-primary">&gt;</a>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>



    <!-- ====================== MODAL THÊM ====================== -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="addForm" enctype="multipart/form-data">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Thêm món ăn</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Tên món</label>
                        <input type="text" name="name" class="form-control mb-2" required>

                        <label>Mô tả</label>
                        <textarea name="description" class="form-control mb-2"></textarea>

                        <label>Danh mục</label>
                        <select name="category_id" class="form-select mb-2" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['category_id'] ?>"><?= $c['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Giá</label>
                        <input type="number" name="price" class="form-control mb-2" required>

                        <label>Số lượng tồn kho</label>
                        <input type="number" name="stock_quantity" class="form-control mb-2" required>

                        <label>Ảnh món ăn</label>
                        <input type="file" name="image" accept="image/*" class="form-control mb-2">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">
                            Thêm mới
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>


    <!-- ====================== MODAL SỬA ====================== -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="editForm" enctype="multipart/form-data">

                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Sửa món ăn</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" id="edit_id" name="food_id">

                        <label>Tên món</label>
                        <input type="text" id="edit_name" name="name" class="form-control mb-2" required>

                        <label>Mô tả</label>
                        <textarea id="edit_description" name="description" class="form-control mb-2"></textarea>

                        <label>Danh mục</label>
                        <select id="edit_category_id" name="category_id" class="form-select mb-2" required>
                            <?php foreach ($categories as $c): ?>
                            <option value="<?= $c['category_id'] ?>"><?= $c['name'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label>Giá</label>
                        <input type="text" id="edit_price" name="price" class="form-control mb-2" required>
                        <label>Số lượng tồn kho</label>
                        <input type="number" id="edit_stock" name="stock_quantity" class="form-control mb-2" required>

                        <label>Ảnh hiện tại</label><br>
                        <img id="edit_preview" class="preview-img mb-2">

                        <label>Đổi ảnh (Không bắt buộc)</label>
                        <input type="file" name="image" accept="image/*" class="form-control mb-2">

                        <div id="editAlert"></div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>

                </div>
            </form>
        </div>
    </div>




    <!-- ======================  JS AJAX ====================== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script>
    const addModal = document.getElementById("addModal");
    const editModal = document.getElementById("editModal");

    const addForm = document.getElementById("addForm");
    const editForm = document.getElementById("editForm");

    const filter_search = document.getElementById("filter_search");
    const foodTableBody = document.getElementById("foodTableBody");
    const ajaxAlert = document.getElementById("ajaxAlert");

    const edit_id = document.getElementById("edit_id");
    const edit_name = document.getElementById("edit_name");
    const edit_desc = document.getElementById("edit_description");
    const edit_cat = document.getElementById("edit_category_id");
    const edit_price = document.getElementById("edit_price");
    const edit_stock = document.getElementById("edit_stock");
    const edit_preview = document.getElementById("edit_preview");


    /* ==========================
       OPEN MODAL THÊM
    ========================== */
    function openAddModal() {
        new bootstrap.Modal(addModal).show();
    }


    function openEditModal(id, name, desc, cat, price, stock, image) {
        edit_id.value = id;
        edit_name.value = name;
        edit_desc.value = desc;
        edit_cat.value = cat;

        // ⭐ Format giá khi hiện modal: 55000 → 55.000
        let formattedPrice = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        edit_price.value = formattedPrice;

        edit_stock.value = stock;
        edit_preview.src = "/upload/foods/" + image;

        new bootstrap.Modal(editModal).show();
    }




    /* ==========================
       AJAX THÊM
    ========================== */
    addForm.addEventListener("submit", function(e) {
        e.preventDefault();

        let formData = new FormData(addForm);

        fetch("/admin/foods/add", {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    ajaxAlert.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show mt-2">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            Thêm món ăn thành công!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;

                    location.reload();
                }

            });
    });


    editForm.addEventListener("submit", function(e) {
        e.preventDefault();

        let id = edit_id.value;

        // ⭐ Bỏ dấu chấm trong giá trước khi gửi đi
        let rawPrice = edit_price.value.replace(/\./g, "");
        let formData = new FormData(editForm);
        formData.set("price", rawPrice);

        fetch("/admin/foods/update/" + id, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    ajaxAlert.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show mt-2">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            Cập nhật món ăn thành công!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;

                    location.reload();
                }

            });
    });



    /* ==========================
       AJAX XÓA
    ========================== */
    function deleteFood(id) {
        if (!confirm("Bạn chắc chắn muốn xóa món ăn này?")) return;

        fetch("/admin/foods/delete/" + id, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    ajaxAlert.innerHTML = `
                        <div class="alert alert-success alert-dismissible fade show mt-2">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            Xóa món ăn thành công!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;

                    location.reload();
                }

            });
    }


    /* ==========================
       AJAX FILTER
    ========================== */
    filter_search.oninput = function() {

        fetch("/admin/foods/filter", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: JSON.stringify({
                    keyword: filter_search.value
                })
            })
            .then(res => res.json())
            .then(data => {
                foodTableBody.innerHTML = data.table;
            });

    };
    </script>

</body>

</html>