<style>
    /* ================= FOOTER ================= */
    .footer {
        background: #0d1b2a;
        color: #dce3e9;
        padding: 50px 60px;
        margin-top: 50px;
    }

    .footer-container {
        display: flex;
        justify-content: space-between;
        gap: 60px;
        flex-wrap: wrap;
    }

    .footer h3 {
        color: #ffffff;
        margin-bottom: 15px;
        font-size: 22px;
    }

    .footer-left .brand {
        font-family: "Brush Script MT", cursive;
        font-size: 40px;
        color: #4cc9f0;
        margin-bottom: 10px;
    }

    .footer-left p {
        width: 260px;
        line-height: 1.5;
    }

    .social-icons i {
        font-size: 22px;
        margin-right: 15px;
        cursor: pointer;
        padding: 10px;
        border-radius: 50%;
        background: #14213d;
        color: #4cc9f0;
        transition: 0.3s;
    }

    .social-icons i:hover {
        background: #4cc9f0;
        color: #fff;
    }

    .footer ul {
        list-style: none;
        padding: 0;
    }

    .footer ul li {
        margin-bottom: 8px;
        font-size: 15px;
    }

    .footer ul li i {
        color: #4cc9f0;
        margin-right: 10px;
    }

    .footer-map img {
        width: 220px;
        border-radius: 8px;
    }

    .footer-bottom {
        margin-top: 35px;
        border-top: 1px solid #23344a;
        padding-top: 20px;
        text-align: center;
        font-size: 14px;
        color: #9aa7b8;
    }
</style>

<footer class="footer">
    <div class="footer-container">

        <!-- LEFT -->
        <div class="footer-left">
            <div class="brand">YummyHub</div>
            <p>Mang đến những trải nghiệm ẩm thực tuyệt vời nhất cho khách hàng Việt Nam.</p>

            <div class="social-icons mt-3">
                <i class="fa-brands fa-facebook-f"></i>
                <i class="fa-brands fa-instagram"></i>
                <i class="fa-brands fa-youtube"></i>
            </div>
        </div>

        <!-- CONTACT -->
        <div>
            <h3>Liên hệ</h3>
            <ul>
                <li><i class="fa fa-location-dot"></i> Lý Nam Đế, Hoàn Kiếm, TP.Hà Nội</li>
                <li><i class="fa fa-phone"></i> 0345 591 612</li>
                <li><i class="fa fa-envelope"></i> yummyhub.0810@gmail.com</li>
            </ul>
        </div>

        <!-- WORKING HOURS -->
        <div>
            <h3>Giờ mở cửa</h3>
            <ul>
                <li>Thứ 2 - Thứ 6: 7:00 - 22:00</li>
                <li>Thứ 7 - Chủ nhật: 6:30 - 23:00</li>
            </ul>
        </div>

        <!-- MAP -->
        <div class="footer-map">
            <h3>Bản đồ</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115162.45852745384!2d84.92633681640626!3d25.577427500000006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f2a9006106c715%3A0xe2dad849d082fb10!2sYummy%20Hub!5e0!3m2!1svi!2s!4v1765052419642!5m2!1svi!2s" width="400" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        © 2024 YummyHub. Tất cả quyền được bảo lưu.
    </div>
</footer>
