@if (!empty($title) && $title == 'landing-page-qr')
    <footer class="main-footer" style="background-image: url('{{ asset('img/BG-LANDING-PAGE-SCAN-QR-EVENT.jpg') }}');">
        <div class="footer-left">
            Copyright &copy; {{ date('Y') }}
            <div class="bullet"></div> PT. Datascrip
        </div>
        <div class="footer-right">
            2.3.0
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
