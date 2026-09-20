/* =========================================================
   Bitline Angola — app.js
   Cursor personalizado, scroll reveal, menu mobile e
   submissão AJAX dos formulários de contacto.
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Cursor personalizado (apenas em ecrãs com rato) ---------- */
  const cursor = document.getElementById('cursor');
  const ring = document.getElementById('cursorRing');
  const temRato = window.matchMedia('(pointer: fine)').matches;

  if (cursor && ring && temRato) {
    document.addEventListener('mousemove', function (e) {
      cursor.style.left = e.clientX - 5 + 'px';
      cursor.style.top = e.clientY - 5 + 'px';
      ring.style.left = e.clientX - 18 + 'px';
      ring.style.top = e.clientY - 18 + 'px';
    });
  } else if (cursor && ring) {
    cursor.style.display = 'none';
    ring.style.display = 'none';
  }

  /* ---------- Scroll reveal ---------- */
  const reveals = document.querySelectorAll('.reveal');
  if (reveals.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry, i) => {
        if (entry.isIntersecting) {
          setTimeout(() => entry.target.classList.add('visible'), i * 80);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });
    reveals.forEach(el => observer.observe(el));
  } else {
    reveals.forEach(el => el.classList.add('visible'));
  }

  /* ---------- Header: efeito ao fazer scroll ---------- */
  const header = document.querySelector('header');
  if (header) {
    const aplicarEstadoScroll = () => header.classList.toggle('scrolled', window.scrollY > 40);
    aplicarEstadoScroll();
    window.addEventListener('scroll', aplicarEstadoScroll);
  }

  /* ---------- Menu mobile (hambúrguer) ---------- */
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');

  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => {
      navToggle.classList.toggle('aberto');
      navLinks.classList.toggle('aberto');
      navToggle.setAttribute('aria-expanded', navLinks.classList.contains('aberto'));
    });

    // Fecha o menu ao clicar num link
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navToggle.classList.remove('aberto');
        navLinks.classList.remove('aberto');
      });
    });
  }

  /* ---------- Formulários de contacto (AJAX) ---------- */
  configurarFormularioContacto('contactForm', 'formResponse');
  configurarFormularioContacto('contactFormFull', 'formResponseFull');
  configurarFormularioContacto('candidaturaForm', 'formResponseCandidatura');

  /* ---------- Painel "tempo real" da hero ---------- */
  try { iniciarPainelTech(); } catch (erro) { console.error('Erro no painel tech:', erro); }

  function iniciarPainelTech() {
    const comandoEl   = document.getElementById('tpCommand');
    const httpsEl      = document.getElementById('tpValorHttps');
    const servidorEl   = document.getElementById('tpValorServidor');
    const latenciaEl   = document.getElementById('tpValorLatencia');
    const relogioEl    = document.getElementById('tpRelogio');
    const linhaServidor = document.getElementById('tpLinhaServidor');
    const linhaLatencia = document.getElementById('tpLinhaLatencia');
    const graphEl       = document.getElementById('tpGraph');
    const uptimeEl       = document.getElementById('tpUptime');

    if (!comandoEl) return; // painel não está nesta página

    // --- Efeito de escrita do comando (uma vez) ---
    const comandoTexto = 'ping bitlineangola.co.ao --live';
    let i = 0;
    (function escrever() {
      if (i <= comandoTexto.length) {
        comandoEl.textContent = comandoTexto.slice(0, i);
        i++;
        setTimeout(escrever, 45);
      }
    })();

    // --- HTTPS: dado real do próprio browser ---
    const seguro = window.location.protocol === 'https:';
    httpsEl.textContent = seguro ? 'ativa' : 'inativa (http)';
    httpsEl.parentElement.classList.toggle('tp-ok', seguro);
    httpsEl.parentElement.classList.toggle('tp-warn', !seguro);

    // --- Relógio real, a atualizar a cada segundo ---
    function atualizarRelogio() {
      relogioEl.textContent = new Date().toLocaleTimeString('pt-PT');
    }
    atualizarRelogio();
    setInterval(atualizarRelogio, 1000);

    // --- Latência real (ping ao próprio servidor) + histórico no gráfico ---
    const historico = [];
    const maxHistorico = 8;
    let tentativas = 0, sucessos = 0;

    function medirLatencia() {
      const inicio = performance.now();
      fetch('actions/ping.php?t=' + Date.now(), { cache: 'no-store' })
        .then(resp => {
          if (!resp.ok) throw new Error('offline');
          return resp.json();
        })
        .then(() => {
          const duracao = Math.round(performance.now() - inicio);
          tentativas++; sucessos++;
          registarMedicao(duracao, true);
        })
        .catch(() => {
          tentativas++;
          registarMedicao(null, false);
        });
    }

    function registarMedicao(ms, ok) {
      servidorEl.textContent = ok ? 'online' : 'sem resposta';
      linhaServidor.classList.toggle('tp-ok', ok);
      linhaServidor.classList.toggle('tp-warn', !ok);

      if (ok) {
        latenciaEl.textContent = ms + ' ms';
        linhaLatencia.classList.remove('tp-warn', 'tp-err');
        linhaLatencia.classList.add(ms < 150 ? 'tp-ok' : 'tp-warn');
        historico.push(ms);
      } else {
        latenciaEl.textContent = 'indisponível';
        linhaLatencia.classList.remove('tp-ok');
        linhaLatencia.classList.add('tp-err');
        historico.push(0);
      }
      if (historico.length > maxHistorico) historico.shift();

      // disponibilidade real desta sessão de navegação
      if (tentativas > 0) {
        uptimeEl.textContent = Math.round((sucessos / tentativas) * 100) + '%';
      }

      atualizarGrafico();
    }

    function atualizarGrafico() {
      if (!graphEl) return;
      const barras = graphEl.querySelectorAll('span');
      const maxVal = Math.max(...historico, 60); // escala mínima de 60ms
      historico.forEach((valor, idx) => {
        const barra = barras[barras.length - historico.length + idx];
        if (!barra) return;
        const altura = valor === 0 ? 6 : Math.max(10, Math.min(100, (valor / maxVal) * 100));
        barra.style.setProperty('--h', altura + '%');
        barra.classList.toggle('tp-bar-err', valor === 0);
      });
    }

    medirLatencia();
    setInterval(medirLatencia, 4000);
  }

  function configurarFormularioContacto(idFormulario, idResposta) {
    const form = document.getElementById(idFormulario);
    if (!form) return;

    const responseDiv = document.getElementById(idResposta);

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const btn = form.querySelector('button');
      const textoOriginal = btn.innerText;
      const formData = new FormData(form);

      btn.disabled = true;
      btn.innerText = 'A enviar...';

      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'fetch' }
      })
        .then(response => response.json())
        .then(data => {
          responseDiv.style.display = 'block';
          if (data.status === 'success') {
            responseDiv.style.color = '#4ade80';
            responseDiv.innerText = data.message;
            form.reset();
          } else {
            responseDiv.style.color = '#f87171';
            responseDiv.innerText = data.message;
          }
        })
        .catch(() => {
          responseDiv.style.display = 'block';
          responseDiv.style.color = '#f87171';
          responseDiv.innerText = 'Ocorreu um erro ao enviar. Tenta novamente ou liga para nós.';
        })
        .finally(() => {
          btn.disabled = false;
          btn.innerText = textoOriginal;
        });
    });
  }

  /* ---------- Pré-preenche o assunto no contacto.php vindo de outra página (ex: ferramenta de recomendação) ---------- */
  const selectAssunto = document.getElementById('assunto');
  if (selectAssunto) {
    const params = new URLSearchParams(window.location.search);
    const assuntoUrl = params.get('assunto');
    if (assuntoUrl) {
      const opcaoExiste = Array.from(selectAssunto.options).some(o => o.value === assuntoUrl);
      if (opcaoExiste) {
        selectAssunto.value = assuntoUrl;
        selectAssunto.closest('.form-group')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  }

  /* ---------- Ferramenta "Qual a Solução Ideal?" ---------- */
  try { iniciarQuiz(); } catch (erro) { console.error('Erro no quiz:', erro); }

  function iniciarQuiz() {
    const box = document.querySelector('.quiz-box');
    if (!box) return;

    const respostas = {};
    let passoAtual = 1;
    const totalPassos = 3;

    const recomendacoes = {
      redes: {
        titulo: 'Infraestrutura de Redes',
        texto: 'Pelo que descreveste, o problema mais provável está na tua rede empresarial. Podemos avaliar a tua infraestrutura atual e propor uma rede mais rápida, estável e segura — com Wi-Fi corporativo, VLANs e firewall.',
        assunto: 'Redes & Infraestrutura',
      },
      ciberseguranca: {
        titulo: 'Cibersegurança',
        texto: 'A segurança da tua empresa deve ser prioridade. Podemos avaliar os riscos atuais e implementar firewalls, monitorização contínua e políticas de segurança para proteger a tua informação.',
        assunto: 'Cibersegurança',
      },
      dados: {
        titulo: 'Criação de Bases de Dados',
        texto: 'Parece que precisas de uma base de dados bem estruturada — segura, organizada e fácil de consultar. Desenhamos e implementamos bases de dados à medida do teu negócio, com backups automáticos incluídos.',
        assunto: 'Criação de Bases de Dados',
      },
      software: {
        titulo: 'Desenvolvimento de Software',
        texto: 'O que precisas é de um sistema ou site feito à tua medida — não um template genérico. Desenvolvemos aplicações web, sistemas de gestão e websites institucionais para o teu negócio.',
        assunto: 'Desenvolvimento de Software',
      },
      suporte: {
        titulo: 'Suporte Técnico',
        texto: 'O que a tua empresa precisa é de uma equipa de confiança sempre disponível. Oferecemos suporte técnico remoto e presencial, com planos de manutenção preventiva.',
        assunto: 'Suporte Técnico',
      },
    };

    box.addEventListener('click', (e) => {
      const opcao = e.target.closest('.quiz-opcao');
      if (opcao) {
        const passoEl = opcao.closest('.quiz-passo');
        const numPasso = parseInt(passoEl.dataset.passo, 10);
        respostas['passo' + numPasso] = opcao.dataset.valor;

        passoEl.querySelectorAll('.quiz-opcao').forEach(b => b.classList.remove('selecionada'));
        opcao.classList.add('selecionada');

        setTimeout(() => {
          if (numPasso < totalPassos) {
            avancarPasso(numPasso + 1);
          } else {
            mostrarResultado();
          }
        }, 220);
      }

      if (e.target.id === 'quizReiniciar') {
        Object.keys(respostas).forEach(k => delete respostas[k]);
        box.querySelectorAll('.quiz-opcao').forEach(b => b.classList.remove('selecionada'));
        box.querySelector('[data-resultado]').style.display = 'none';
        avancarPasso(1);
      }
    });

    function avancarPasso(numPasso) {
      passoAtual = numPasso;
      box.querySelectorAll('.quiz-passo').forEach(p => {
        p.style.display = parseInt(p.dataset.passo, 10) === numPasso ? 'block' : 'none';
      });
      box.querySelectorAll('.quiz-progresso span').forEach(s => {
        s.classList.toggle('quiz-passo-ativo', parseInt(s.dataset.passoNum, 10) <= numPasso);
      });
    }

    function mostrarResultado() {
      const chave = respostas.passo1;
      const rec = recomendacoes[chave] || recomendacoes.suporte;

      box.querySelectorAll('.quiz-passo').forEach(p => p.style.display = 'none');
      box.querySelectorAll('.quiz-progresso span').forEach(s => s.classList.add('quiz-passo-ativo'));

      document.getElementById('quizResultadoTitulo').textContent = rec.titulo;
      document.getElementById('quizResultadoTexto').textContent = rec.texto;
      document.getElementById('quizResultadoCta').href = 'contacto.php?assunto=' + encodeURIComponent(rec.assunto);

      box.querySelector('[data-resultado]').style.display = 'block';
    }
  }

  /* ---------- Acordeão do FAQ ---------- */
  document.querySelectorAll('.faq-pergunta').forEach((btn) => {
    btn.addEventListener('click', () => {
      const item = btn.closest('.faq-item');
      const jaAberto = item.classList.contains('aberto');
      item.closest('.faq-acordeao').querySelectorAll('.faq-item').forEach(i => i.classList.remove('aberto'));
      if (!jaAberto) item.classList.add('aberto');
    });
  });

});
