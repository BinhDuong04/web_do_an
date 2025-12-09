<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý danh mục</title>

    <!-- Bootstrap 5 -->
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

        .card.fixed-height {
            height: 500px;
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
            <a class="nav-link active" href="/admin/categories"><i class="fa-solid fa-layer-group"></i> Quản lý danh mục</a>
            <a class="nav-link" href="/admin/foods"><i class="fa-solid fa-bowl-food"></i> Quản lý món ăn</a>
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
            <h2 class="page-title">Quản lý danh mục</h2>
            <button class="btn btn-primary" onclick="openAddModal()">
                <i class="fa fa-plus"></i> Thêm danh mục
            </button>
        </div>

        <div class="card shadow-sm fixed-height">
            <div class="card-body table-container">

                <div id="ajaxAlert"></div>

                <!-- TÌM KIẾM -->
                <div class="row mb-3">
                    <div class="col-md-12 text-end">
                        <input type="text" id="filter_search" class="form-control w-50 ms-auto" placeholder="Tìm kiếm danh mục...">
                    </div>
                </div>

                <!-- BẢNG DANH MỤC -->
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th width="120px">Hành động</th>
                        </tr>
                    </thead>

                    <tbody id="categoryTableBody">
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $c): ?>
                                <tr>
                                    <td><?= $c['category_id'] ?></td>
                                    <td><?= esc($c['name']) ?></td>
                                    <td><?= esc($c['description']) ?></td>

                                    <td>
                                        <button class="btn btn-warning btn-sm"
                                            onclick="openEditModal(
                                                <?= $c['category_id'] ?>,
                                                '<?= esc($c['name'], 'js') ?>',
                                                '<?= esc($c['description'], 'js') ?>'
                                            )">
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm"
                                                onclick="deleteCategory(<?= $c['category_id'] ?>)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger fw-bold">
                                    Không có danh mục nào
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <?php if (isset($currentPage) && isset($totalPages) && $totalPages > 0): ?>
                        <div class="pagination d-flex">

                            <?php if ($currentPage > 1): ?>
                                <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-outline-primary me-1">&lt;</a>
                                <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-outline-primary me-1">
                                    <?= $currentPage - 1 ?>
                                </a>
                            <?php endif; ?>

                            <span class="btn btn-primary me-1">
                                <?= $currentPage ?>
                            </span>

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

    <!-- MODAL THÊM -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="addForm">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Thêm danh mục</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Tên danh mục</label>
                        <input type="text" name="name" class="form-control mb-2" required>

                        <label>Mô tả</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SỬA -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm">
                <div class="modal-content">

                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title">Sửa danh mục</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="category_id">

                        <label>Tên danh mục</label>
                        <input type="text" id="edit_name" name="name" class="form-control mb-2" required>

                        <label>Mô tả</label>
                        <textarea id="edit_description" name="description" class="form-control"></textarea>

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

    <!-- JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script>
        const addModal  = document.getElementById("addModal");
        const editModal = document.getElementById("editModal");

        const addForm  = document.getElementById("addForm");
        const editForm = document.getElementById("editForm");

        const ajaxAlert = document.getElementById("ajaxAlert");
        const categoryTableBody = document.getElementById("categoryTableBody");

        const edit_id          = document.getElementById("edit_id");
        const edit_name        = document.getElementById("edit_name");
        const edit_description = document.getElementById("edit_description");
        const editAlert        = document.getElementById("editAlert");

        const filter_search = document.getElementById("filter_search");

        function openAddModal() {
            new bootstrap.Modal(addModal).show();
        }

        function openEditModal(id, name, description) {
            edit_id.value = id;
            edit_name.value = name;
            edit_description.value = description;

            editAlert.innerHTML = "";

            new bootstrap.Modal(editModal).show();
        }

        /* ============================
           AJAX THÊM
        ============================ */
        addForm.addEventListener("submit", function(e){
            e.preventDefault();

            let formData = new FormData(this);

            fetch("/admin/categories/add", {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    categoryTableBody.innerHTML = data.table;

                    ajaxAlert.innerHTML = `
                        <div class="alert alert-success mt-2 alert-dismissible fade show">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            Thêm danh mục thành công!
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;

                    bootstrap.Modal.getInstance(addModal).hide();
                    addForm.reset();

                } else {
                    ajaxAlert.innerHTML = `
                        <div class="alert alert-danger mt-2 alert-dismissible fade show">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            ${data.error}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;
                }

            });
        });

        /* ============================
           AJAX CẬP NHẬT
        ============================ */
        editForm.addEventListener("submit", function(e){
            e.preventDefault();

            let id = edit_id.value;
            let formData = new FormData(this);

            fetch("/admin/categories/update/" + id, {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: formData
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    categoryTableBody.innerHTML = data.table;

                    editAlert.innerHTML = `
                        <div class="alert alert-success mt-2 alert-dismissible fade show">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            Cập nhật danh mục thành công!
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;

                    setTimeout(() => {
                        bootstrap.Modal.getInstance(editModal).hide();
                    }, 800);

                } else {
                    editAlert.innerHTML = `
                        <div class="alert alert-danger mt-2 alert-dismissible fade show">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            ${data.error}
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;
                }

            });
        });

        /* ============================
           AJAX XÓA
        ============================ */
        function deleteCategory(id) {
            if (!confirm("Bạn có chắc chắn muốn xóa danh mục này?")) return;

            fetch("/admin/categories/delete/" + id, {
                method: "POST",
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {

                if (data.success) {
                    categoryTableBody.innerHTML = data.table;

                    ajaxAlert.innerHTML = `
                        <div class="alert alert-success mt-2 alert-dismissible fade show">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            Xóa danh mục thành công!
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;
                }

            });
        }

        /* ============================
           AJAX FILTER SEARCH
        ============================ */
        filter_search.oninput = function () {
            fetch("/admin/categories/filter", {
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
                categoryTableBody.innerHTML = data.table;
            });
        };
    </script>

</body>
</html>
