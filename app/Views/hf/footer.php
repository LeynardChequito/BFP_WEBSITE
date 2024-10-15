<?php
$currentYear = date('Y');
?>

<style>
    body {
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .footer {
        background-image: linear-gradient(to bottom, #000000, #2F4F4F);
        color: #A0A0A0;
        padding: 20px;
        margin-top: auto;
    }

    .footer-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-content {
        flex: 1 1 300px;
        margin: 10px;
    }

    .footer-content strong {
        display: block;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .footer-content p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .footer-content span {
        font-size: 18px;
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .footer-content {
            flex: 1 1 100%;
        }

        .footer-content.mobile-hidden {
            display: none;
        }

        .footer-content p {
            font-size: 12px;
        }

        .footer-content span {
            font-size: 14px;
        }
    }
</style>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-content mobile-hidden">
            <strong>REPUBLIC OF THE PHILIPPINES</strong>
            <p>All content is in the public domain unless otherwise stated</p>
        </div>
        <div class="footer-content">
            <strong>Bureau of Fire Protection</strong>
            <p class="desktop-only">Guinobatan, Calapan City, Oriental Mindoro<br>
            #ValiantFirefightersOfCalapanCity<br>
            ORIENTAL MINDORO, MIMAROPA</p>
            <p class="mobile-only">Calapan City, Oriental Mindoro</p>
        </div>
        <div class="footer-content">
            <span><strong>BFP CALAPAN CITY<br>OFFICIAL WEBSITE &copy; <?= $currentYear ?></strong></span>
        </div>
    </div>
</footer>

<script>
    function updateFooterContent() {
        var desktopOnly = document.querySelectorAll('.desktop-only');
        var mobileOnly = document.querySelectorAll('.mobile-only');
        
        if (window.innerWidth <= 768) {
            desktopOnly.forEach(function(el) { el.style.display = 'none'; });
            mobileOnly.forEach(function(el) { el.style.display = 'block'; });
        } else {
            desktopOnly.forEach(function(el) { el.style.display = 'block'; });
            mobileOnly.forEach(function(el) { el.style.display = 'none'; });
        }
    }

    window.addEventListener('load', updateFooterContent);
    window.addEventListener('resize', updateFooterContent);
</script>