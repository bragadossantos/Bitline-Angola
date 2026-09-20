<?php
/*
|--------------------------------------------------------------------------
| FOOTER DO SITE
|--------------------------------------------------------------------------
*/
?>

</main>

<a href="https://wa.me/244<?= CONTACTO_UNITEL ?>" class="whatsapp-float" target="_blank" aria-label="Falar no WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<footer class="footer">

    <div class="container footer-grid">

        <!-- Empresa -->
        <div class="footer-col footer-brand">

            <a href="index.php" class="logo footer-logo">
                <img src="assets/images/logo-icon.png" alt="Bitline Angola" class="logo-icon-img">
                <span class="logo-text">
                    <span class="logo-name">Bit<span class="accent">line</span></span>
                    <small>Angola</small>
                </span>
            </a>

            <span class="footer-tagline">Soluções Inteligentes, Resultados Reais.</span>

            <p>
                Empresa angolana especializada em soluções tecnológicas:
                infraestrutura de redes, cibersegurança,
                desenvolvimento de software, cloud,
                telecomunicações, Criação de Bases de Dados
                e suporte técnico.
            </p>

            <div class="social-icons">
                <a href="https://www.facebook.com/profile.php?id=61578654713490" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/bitlineangola?igsh=bHNhMjUwMnJldWI%3D&utm_source=qr" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/company/bitline-angola" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://wa.me/244<?= CONTACTO_UNITEL ?>" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>

        </div>

        <!-- Contactos -->
        <div class="footer-col">

            <h3>Contactos</h3>

            <ul>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    UNITEL:
                    <a href="tel:+244<?= CONTACTO_UNITEL ?>"><?= formatarTelefone(CONTACTO_UNITEL) ?></a>
                </li>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    AFRICELL:
                    <a href="tel:+244<?= CONTACTO_AFRICELL ?>"><?= formatarTelefone(CONTACTO_AFRICELL) ?></a>
                </li>
                <li>
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:bitlineangola@outlook.com">bitlineangola@outlook.com</a>
                </li>
                <li>
                    <i class="fa-solid fa-location-dot"></i>
                    Luanda / Angola
                </li>
            </ul>

        </div>

        <!-- Links -->
        <div class="footer-col">

            <h3>Links Rápidos</h3>

            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="index.php#servicos">Serviços</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="portfolio.php">Portefólio</a></li>
                <li><a href="qual-solucao.php">Qual a Solução Ideal?</a></li>
                <li><a href="faq.php">FAQ</a></li>
                <li><a href="trabalha-connosco.php">Trabalha Connosco</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="admin/login.php">Área Administrativa</a></li>
            </ul>

        </div>

        <!-- Serviços -->
        <div class="footer-col">

            <h3>Serviços</h3>

            <ul>
                <li><a href="index.php#servicos">Infraestrutura de Redes</a></li>
                <li><a href="index.php#servicos">Cibersegurança</a></li>
                <li><a href="index.php#servicos">Cloud Computing</a></li>
                <li><a href="index.php#servicos">Desenvolvimento de Software</a></li>
                <li><a href="index.php#servicos">Criação de Bases de Dados</a></li>
                <li><a href="index.php#servicos">Suporte Técnico</a></li>
            </ul>

        </div>

    </div>

    <div class="footer-bottom">
        © <?= date('Y') ?> Bitline Angola. Todos os direitos reservados.
        <a href="politica-privacidade.php" style="color: var(--grey);">Política de Privacidade</a>
        <!-- <span class="footer-made">Feito com <i class="fa-solid fa-circle"></i> em Luanda</span> -->
    </div>

</footer>

<script src="js/app.js?v=<?= @filemtime(__DIR__ . '/../js/app.js') ?>"></script>

</body>

</html>
