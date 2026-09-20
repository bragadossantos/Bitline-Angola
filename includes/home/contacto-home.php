<!-- =====================================================
                    CONTACTO (RÁPIDO)
====================================================== -->

<section class="contact-home" id="contacto">

    <div class="container">

        <div class="contact-home-box reveal">

            <div class="contact-home-text">
                <span class="section-subtitle">FALA CONNOSCO</span>
                <h2>Pronto para transformar a sua empresa?</h2>
                <p>A primeira consultoria é gratuita. Vamos perceber juntos qual é a melhor solução tecnológica para o seu negócio.</p>

                <div class="contact-links">
                    <a href="tel:+244<?= CONTACTO_UNITEL ?>" class="contact-link">
                        <i class="fa-solid fa-phone"></i> <?= formatarTelefone(CONTACTO_UNITEL) ?> <small>(Unitel)</small>
                    </a>
                    <a href="tel:+244<?= CONTACTO_AFRICELL ?>" class="contact-link">
                        <i class="fa-solid fa-phone"></i> <?= formatarTelefone(CONTACTO_AFRICELL) ?> <small>(Africell)</small>
                    </a>
                    <a href="mailto:bitlineangola@outlook.com" class="contact-link">
                        <i class="fa-solid fa-envelope"></i> bitlineangola@outlook.com
                    </a>
                </div>
            </div>

            <form class="contact-form-rapido" id="contactForm" action="actions/processar_contacto.php" method="POST">
                <?php mostrarMensagemFormularioSemJS(); ?>
                <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
                <input type="text" name="website_url_check" style="display:none" tabindex="-1" autocomplete="off">

                <div class="form-group">
                    <label for="nomeRapido">Nome</label>
                    <input type="text" id="nomeRapido" name="nome" placeholder="O teu nome">
                </div>
                <div class="form-group">
                    <label for="emailRapido">Email profissional *</label>
                    <input type="email" id="emailRapido" name="email" placeholder="nome@empresa.co.ao" required>
                </div>
                <div class="form-group">
                    <label for="mensagemRapida">Mensagem *</label>
                    <textarea id="mensagemRapida" name="mensagem" rows="3" placeholder="Conta-nos sobre o teu projeto..." required></textarea>
                </div>
                <button type="submit">Enviar Mensagem <i class="fa-solid fa-paper-plane"></i></button>
                <div id="formResponse" style="display:none;"></div>
            </form>

        </div>

    </div>

</section>
