<!-- =====================================================
                        HERO
====================================================== -->

<section class="hero">

    <div class="container hero-container">

        <div class="hero-text reveal">

            <span class="badge"><i class="dot"></i> Bem-vindo à Bitline Angola</span>

            <h1>
                Soluções Inteligentes em
                <span class="grad">Tecnologia da Informação</span>
            </h1>

            <p>
                Desenvolvemos soluções completas em Infraestrutura
                de Redes, Cibersegurança, Cloud Computing,
                Desenvolvimento de Software, Telecomunicações
                e Suporte Técnico para empresas de todos os portes.
            </p>

            <div class="hero-buttons">
                <a href="contacto.php" class="btn-primary">
                    Solicitar Orçamento
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="blog.php" class="btn-secondary">Explorar o Blog</a>
            </div>

            <div class="hero-mini-stats">
                <div><strong>2+</strong><span>Anos</span></div>
                <div><strong>12+</strong><span>Projetos</span></div>
                <div><strong>87%</strong><span>Satisfação</span></div>
            </div>

        </div>

        <div class="hero-visual reveal">
            <div class="tech-panel">
                <div class="tech-panel-head">
                    <span class="tp-dot red"></span><span class="tp-dot yellow"></span><span class="tp-dot green"></span>
                    <span class="tp-title">bitline_network — status ao vivo</span>
                    <span class="tp-live"><i></i> LIVE</span>
                </div>
                <div class="tech-panel-body">
                    <div class="tp-line"><span class="tp-prompt">$</span> <span id="tpCommand"></span></div>

                    <div class="tp-line tp-ok" id="tpLinhaHttps">
                        <i class="fa-solid fa-lock"></i> Ligação segura (HTTPS) <span class="tp-valor" id="tpValorHttps">a verificar…</span>
                    </div>
                    <div class="tp-line" id="tpLinhaServidor">
                        <i class="fa-solid fa-server"></i> Servidor Bitline <span class="tp-valor" id="tpValorServidor">a ligar…</span>
                    </div>
                    <div class="tp-line" id="tpLinhaLatencia">
                        <i class="fa-solid fa-gauge-high"></i> Latência real <span class="tp-valor" id="tpValorLatencia">— ms</span>
                    </div>
                    <div class="tp-line tp-muted">
                        <i class="fa-solid fa-clock"></i> Última verificação: <span id="tpRelogio">--:--:--</span>
                    </div>

                    <div class="tp-graph" id="tpGraph">
                        <span style="--h:8%"></span><span style="--h:8%"></span><span style="--h:8%"></span>
                        <span style="--h:8%"></span><span style="--h:8%"></span><span style="--h:8%"></span>
                        <span style="--h:8%"></span><span style="--h:8%"></span>
                    </div>
                    <div class="tp-graph-label">Histórico de latência (últimas medições reais)</div>
                </div>
            </div>
            <div class="tech-float-card">
                <i class="fa-solid fa-shield-halved"></i>
                <div><strong id="tpUptime">100%</strong><span>Disponibilidade da sessão</span></div>
            </div>
        </div>

    </div>
</section>
