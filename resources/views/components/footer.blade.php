@if (!empty($title) && $title == 'landing-page-qr')
    <footer class="main-footer"
        style="background-image: url('{{ asset('img/BG-LANDING-PAGE-SCAN-QR-EVENT.jpg') }}'); display: flex; justify-content: center; align-items: center; height: 100px; width: 100%; padding: 20px;">
        <div class="footer-content" style="text-align: center;">
            <div class="footer-left">
                <span style="color: #000000;">
                    Copyright &copy; 2024
                </span>
                <div class="bullet"></div>
                <span style="color: #000000;">
                    PT. Datascrip
                </span>
            </div>
        </div>
    </footer>
@else
    <footer class="main-footer">
        <div class="footer-left" style="color: #000000">
            Copyright &copy; 2024
            <div class="bullet"></div> PT. Datascrip
        </div>
    </footer>
@endif
