@if (!empty($title) && $title == 'landing-page-qr')
    <footer class="main-footer"
        style="background-image: url('{{ asset('img/BG-LANDING-PAGE-SCAN-QR-EVENT.jpg') }}'); display: flex; justify-content: center; align-items: center; height: 100px; width: 100%; padding: 20px;">
        <div class="footer-content" style="text-align: center;">
            <div class="footer-left">
                <span style="color: white;">
                    Copyright &copy; {{ date('Y') }}
                </span>
                <div class="bullet"></div>
                <span style="color: white;">
                    Datascrip
                </span>
            </div>
            <div class="footer-right">
                2.3.0
            </div>
        </div>
    </footer>
@else
    <footer class="main-footer">
        <div class="footer-left">
            Copyright &copy; {{ date('Y') }}
            <div class="bullet"></div> PT. Datascrip
        </div>
        <div class="footer-right">
            2.3.0
        </div>
    </footer>
@endif
