<?php
$session = session();
$isLoggedIn = $session->get('user_id');
$userName   = $session->get('username');
$email      = $session->get('email');
$phone      = $session->get('phone');
$address    = $session->get('address');
$cartCount  = $session->get('cart_count') ?? 0;
?>
<style>
/* ================= HEADER FIXED ================= */
.top-header,
.menu-bar {
    position: sticky;
    background: white;
    z-index: 999;
}

.top-header {
    top: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 40px;
    border-bottom: 1px solid #eee;
    transition: box-shadow 0.25s ease-in-out;
}

/* Đổ bóng khi cuộn */
.header-shadow {
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
}

/* Logo */
.brand {
    font-family: "Brush Script MT", cursive;
    font-size: 38px;
    font-weight: bold;
    color: #00a8ff;
    cursor: pointer;
    letter-spacing: 1px;
}

/* SEARCH BAR */
.search-box {
    width: 25%;
    position: relative;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: #999;
}

.search-box input {
    width: 100%;
    padding: 10px 45px;
    border-radius: 30px;
    border: 1px solid #ddd;
    font-size: 15px;
    background: #f8f8f8;
    outline: none;
}

/* ICON AREA */
.icon-area i {
    font-size: 22px;
    margin-left: 20px;
    cursor: pointer;
    color: #404040;
    position: relative;
}

.cart-badge {
    position: absolute;
    top: -8px;
    right: -6px;
    background: red;
    color: white;
    padding: 4px 4px;
    border-radius: 50%;
    font-size: 10px;
}

/* LOGIN BUTTON */
.login-btn {
    margin-left: 20px;
    padding: 8px 20px;
    border-radius: 20px;
    border: 1px solid #00a8ff;
    background: white;
    color: #00a8ff;
    font-weight: 500;
    cursor: pointer;
}

.login-btn:hover {
    background: #00a8ff;
    color: white;
}

/* USER DROPDOWN */
.user-box {
    position: absolute;
    top: 55px;
    right: 40px;
    width: 240px;
    background: white;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
    display: none;
    z-index: 1000;
}

.logout-btn {
    width: 100%;
    padding: 8px;
    background: #e74c3c;
    border: none;
    color: white;
    border-radius: 5px;
    cursor: pointer;
}

.logout-btn:hover {
    opacity: 0.9;
}

/* MENU NAV */
.menu-bar {
    position: sticky;
    top: 70px;
    /* nằm ngay dưới top-header */
    z-index: 998;
    background: white;
    border-bottom: 1px solid #eee;

    height: 50px;
    /* CỐ ĐỊNH CHIỀU CAO MENU */
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 80px;
}

.menu-bar a {
    text-decoration: none;
    color: #333;
    font-size: 17px;
    font-weight: 500;
}

.menu-bar a:hover,
.menu-bar .active {
    color: #00a8ff;
    font-weight: bold;
}
</style>

<!-- ================= TOP HEADER ================= -->
<div class="top-header" id="headerSticky">

    <!-- LOGO -->
    <div onclick="window.location='/'" class="brand">YummyHub</div>

    <!-- SEARCH BAR -->
    <div class="search-box">
        <i class="fa fa-search"></i>
        <input type="text" placeholder="Tìm kiếm món ăn yêu thích...">
    </div>

    <!-- ICONS -->
    <div class="icon-area">

        <!-- CART -->
        <i class="fa fa-shopping-cart" onclick="window.location='/cart'">
            <span class="cart-badge"><?= $cartCount ?? 0 ?></span>
        </i>

        <!-- NOTIFICATION -->
        <i class="fa fa-bell"></i>

        <!-- LOGIN / USER -->
        <?php if (!$isLoggedIn): ?>
        <button class="login-btn" onclick="window.location='/login'">Đăng nhập</button>
        <?php else: ?>
        <i class="fa fa-user" onclick="toggleUserBox()"></i>

        <div id="userBox" class="user-box">
            <h6 style="color: #00a8ff; font-weight: bold;"><?= esc($userName) ?></h6>
            <p>Email: <?= esc($email) ?></p>
            <p>SĐT: <?= esc($phone) ?></p>
            <p>Địa chỉ: <?= esc($address) ?></p>
            <button class="logout-btn" onclick="window.location='/logout'">Đăng xuất</button>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ================= MENU BAR ================= -->
<div class="menu-bar">
    <a href="/" class="<?= (current_url() == base_url('/')) ? 'active' : '' ?>">Trang chủ</a>
    <a href="/foods" class="<?= service('uri')->getSegment(1) == 'foods' ? 'active' : '' ?>">Món ăn</a>
    <a href="/orders" class="<?= service('uri')->getSegment(1) == 'orders' ? 'active' : '' ?>">Đơn hàng</a>
    <a href="/order-history" class="<?= service('uri')->getSegment(1) == 'order-history' ? 'active' : '' ?>">Lịch sử đơn
        hàng</a>
</div>

<script>
// Toggle user dropdown
function toggleUserBox() {
    let box = document.getElementById("userBox");
    box.style.display = (box.style.display === "block") ? "none" : "block";
}

// Close dropdown when clicking outside
document.addEventListener("click", function(e) {
    let box = document.getElementById("userBox");
    if (!e.target.closest(".fa-user") && !e.target.closest("#userBox")) {
        box.style.display = "none";
    }
});

// Add shadow when scrolling
window.addEventListener("scroll", function() {
    let header = document.getElementById("headerSticky");
    if (window.scrollY > 10) {
        header.classList.add("header-shadow");
    } else {
        header.classList.remove("header-shadow");
    }
});
</script>