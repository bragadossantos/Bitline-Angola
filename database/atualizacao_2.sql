
-- =========================================================

USE bitlinea_ngola;

-- ---------------------------------------------------------

-- ---------------------------------------------------------
INSERT INTO admins (nome, email, password, criado_em) VALUES
('CEO Bitline Angola', 'ceo@bitlineangola.co.ao',
 '$2b$10$bBDPfiA69IYHmWQN9NIQdeH0x/8HX6DRPKRgx1bwea3Q8jApOQ3vG', NOW())
ON DUPLICATE KEY UPDATE email = email;

INSERT INTO admins (nome, email, password, criado_em) VALUES
('COO Bitline Angola', 'coo@bitlineangola.co.ao',
 '$2b$10$GfjPEvPxK9PWACMncwnWzOSbPBWbipbAdsa5JxxFAqJQ8S5D85YM6', NOW())
ON DUPLICATE KEY UPDATE email = email;


-- ---------------------------------------------------------
-- Projetos de exemplo para o Portefólio
-- (edita à vontade no painel admin depois)
-- ---------------------------------------------------------
INSERT INTO portfolio (titulo, categoria, descricao, cliente, destaque, ordem, criado_em) VALUES
('Rede Corporativa de Alta Disponibilidade', 'Redes',
 'Desenho e implementação de uma rede empresarial com VLANs segmentadas, Wi-Fi corporativo e firewall dedicado, eliminando as quebras de ligação que afetavam o atendimento diário.',
 'Empresa privada, Luanda', 1, 1, NOW()),

('Auditoria e Reforço de Cibersegurança', 'Cibersegurança',
 'Avaliação completa da infraestrutura existente, identificação de vulnerabilidades críticas e implementação de firewall, políticas de acesso e monitorização contínua.',
 'Instituição privada', 0, 2, NOW()),

('Migração para Cloud com Backup Automatizado', 'Cloud Computing',
 'Migração de servidores locais para ambiente cloud híbrido, com backup automático diário e plano de recuperação de desastre testado e documentado.',
 'PME de serviços', 0, 3, NOW()),

('Sistema de Gestão Interna à Medida', 'Desenvolvimento de Software',
 'Desenvolvimento de um sistema web para gestão de stock, vendas e clientes, substituindo processos manuais em folhas de cálculo e reduzindo erros operacionais.',
 'Empresa de retalho', 0, 4, NOW()),

('Base de Dados Centralizada para Multi-Filiais', 'Bases de Dados',
 'Modelação e implementação de uma base de dados centralizada, permitindo que várias filiais partilhassem informação em tempo real com segurança e controlo de acessos.',
 'Rede de lojas', 0, 5, NOW());


-- ---------------------------------------------------------
-- Artigos de blog adicionais (tecnologia, curiosidades, desafios)
-- ---------------------------------------------------------
INSERT INTO artigos (titulo, slug, resumo, conteudo, status, destaque, autor_id, criado_em) VALUES

('5 Sinais de que a Tua Empresa Precisa de um Upgrade de TI',
 '5-sinais-que-a-tua-empresa-precisa-de-upgrade-de-ti',
 'Lentidão constante, quedas de rede e falta de backups são só o início. Descobre os sinais que não deves ignorar.',
 '<p>Muitas empresas só investem em tecnologia depois de um problema grave acontecer — um sistema que cai, dados perdidos, ou um cliente que não conseguiu ser atendido a tempo. Mas há sinais que aparecem muito antes disso.</p>
  <h2>1. Internet lenta nas horas de maior movimento</h2>
  <p>Se a rede fica lenta precisamente quando mais precisas dela, é sinal de que a infraestrutura já não acompanha o crescimento da equipa.</p>
  <h2>2. Quedas de rede frequentes</h2>
  <p>Uma queda ocasional acontece. Quedas frequentes indicam equipamento desatualizado ou mal dimensionado para o número de dispositivos ligados.</p>
  <h2>3. Ausência de backups automáticos</h2>
  <p>Se ainda dependes de alguém se lembrar de copiar ficheiros manualmente, o risco de perda de dados é real — e mais comum do que se pensa.</p>
  <h2>4. Equipamento com mais de 5 anos sem manutenção</h2>
  <p>Routers, switches e servidores têm um ciclo de vida. Depois de certo ponto, o custo de manter equipamento antigo ultrapassa o custo de o substituir.</p>
  <h2>5. Ninguém sabe dizer se a empresa está protegida</h2>
  <p>Se a resposta a "estamos seguros contra ciberataques?" é um encolher de ombros, isso já é, por si só, um sinal de alerta.</p>
  <p>Se reconheceste dois ou mais destes sinais, vale a pena marcar uma avaliação gratuita com a nossa equipa.</p>',
 'publicado', 1, 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),


('Cloud ou Servidor Local? Como Decidir Sem Complicar',
 'cloud-ou-servidor-local-como-decidir',
 'Não existe resposta certa para todos — existe a resposta certa para o teu negócio. Percebe as diferenças antes de decidir.',
 '<p>Uma das perguntas mais frequentes que recebemos é: "devemos migrar tudo para a cloud, ou manter os nossos próprios servidores?" A resposta honesta é: depende.</p>
  <h2>Quando a cloud faz mais sentido</h2>
  <ul>
    <li>Equipas que precisam de acesso remoto frequente</li>
    <li>Empresas que querem evitar o custo inicial de hardware</li>
    <li>Negócios que crescem rápido e precisam de escalar sem complicações</li>
  </ul>
  <h2>Quando o servidor local ainda vale a pena</h2>
  <ul>
    <li>Operações com grandes volumes de dados locais e acesso constante</li>
    <li>Zonas com internet menos estável</li>
    <li>Requisitos específicos de controlo total sobre a infraestrutura</li>
  </ul>
  <p>Na prática, muitas empresas angolanas beneficiam de um modelo híbrido — parte da operação em cloud, parte em infraestrutura local. O importante é decidir com base no que a tua operação realmente precisa, não na tendência do momento.</p>',
 'publicado', 0, 1, DATE_SUB(NOW(), INTERVAL 5 DAY)),


('Sabias Que? 5 Curiosidades sobre Cibersegurança que Poucos Conhecem',
 'curiosidades-sobre-ciberseguranca',
 'Do primeiro vírus informático ao tempo médio que um hacker demora a invadir uma senha fraca — factos que talvez não soubesses.',
 '<p>A cibersegurança está cheia de números e factos surpreendentes. Aqui ficam 5 que vale a pena conheceres:</p>
  <h2>1. O primeiro "vírus" não foi malicioso</h2>
  <p>O Creeper, criado em 1971, foi um programa experimental que apenas exibia a mensagem "Sou o Creeper, apanha-me se puderes" — não tinha intenção de causar dano.</p>
  <h2>2. Senhas fracas caem em segundos</h2>
  <p>Uma senha de 6 caracteres só com letras minúsculas pode ser descoberta por um computador comum em poucos segundos. Adicionar números, símbolos e comprimento muda tudo.</p>
  <h2>3. A maioria dos ataques não é sofisticada</h2>
  <p>Contrariamente ao que os filmes mostram, a maior parte dos ataques bem-sucedidos explora falhas simples e já conhecidas — não técnicas avançadas de "hacking" de Hollywood.</p>
  <h2>4. O elo mais fraco é quase sempre humano</h2>
  <p>Mais de 80% dos incidentes de segurança começam com uma ação humana — um clique num link errado, uma password partilhada, um anexo aberto sem verificar.</p>
  <h2>5. A prevenção custa sempre menos que a reparação</h2>
  <p>Investir em segurança antes de um incidente é, quase sempre, significativamente mais barato do que lidar com as consequências depois.</p>',
 'publicado', 0, 1, DATE_SUB(NOW(), INTERVAL 9 DAY)),


('Desafio Bitline: Consegues Identificar um Email de Phishing?',
 'desafio-bitline-identificar-phishing',
 'Um pequeno desafio para testares o teu olho treinado — e aprenderes a proteger a tua empresa de um dos ataques mais comuns.',
 '<p>O phishing continua a ser uma das formas mais eficazes de ataque — não porque seja sofisticado, mas porque explora a confiança e a pressa do dia a dia.</p>
  <p>Antes de continuares, pensa: já reparaste bem num email suspeito antes de clicares? Aqui ficam os sinais mais comuns a que deves prestar atenção:</p>
  <h2>Sinais de alerta</h2>
  <ul>
    <li><strong>Urgência artificial</strong> — "A tua conta será bloqueada em 24 horas" é uma tática clássica de pressão.</li>
    <li><strong>Domínio de email estranho</strong> — verifica sempre o endereço completo do remetente, não só o nome apresentado.</li>
    <li><strong>Erros de ortografia ou formatação estranha</strong> — empresas sérias raramente enviam comunicações com erros óbvios.</li>
    <li><strong>Links que não batem certo</strong> — passa o rato por cima do link (sem clicar) e confirma se o endereço corresponde ao que esperarias.</li>
    <li><strong>Pedidos de dados sensíveis</strong> — bancos e instituições sérias não pedem passwords ou dados de cartão por email.</li>
  </ul>
  <h2>O desafio</h2>
  <p>Da próxima vez que receberes um email a pedir uma ação urgente, para 10 segundos antes de clicares. Verifica o remetente, o link, e pergunta-te: "isto faz sentido vindo desta fonte?"</p>
  <p>Se a tua empresa quiser treinar a equipa para reconhecer estes sinais na prática, a Bitline Angola pode ajudar com formação dedicada.</p>',
 'publicado', 0, 1, DATE_SUB(NOW(), INTERVAL 13 DAY)),


('Porque É Que a Manutenção Preventiva de TI Compensa Sempre',
 'manutencao-preventiva-de-ti-compensa',
 'Esperar que algo avarie para agir sai quase sempre mais caro do que prevenir. Explicamos porquê, com exemplos simples.',
 '<p>Há uma diferença enorme entre manutenção <strong>corretiva</strong> (resolver o problema depois de acontecer) e manutenção <strong>preventiva</strong> (evitar que aconteça).</p>
  <h2>O custo escondido de "só resolver quando avariar"</h2>
  <p>Quando um servidor falha sem aviso, o custo não é só o reparo — é também o tempo de paragem, a produtividade perdida, e em alguns casos, dados que não voltam a existir.</p>
  <h2>O que entra numa boa manutenção preventiva</h2>
  <ul>
    <li>Atualizações de software e sistemas operativos feitas com regularidade</li>
    <li>Verificação periódica do estado físico de equipamento (servidores, switches, routers)</li>
    <li>Testes reais de backup — não basta ter backup, é preciso confirmar que funciona</li>
    <li>Monitorização contínua de desempenho, para detetar problemas antes de se tornarem críticos</li>
  </ul>
  <h2>Em números simples</h2>
  <p>Um plano de manutenção mensal custa uma fração do que custa um dia inteiro de paragem numa empresa que depende dos seus sistemas para operar. A matemática, na maioria dos casos, fala por si.</p>
  <p>A Bitline Angola oferece planos de suporte e manutenção preventiva ajustados à dimensão de cada empresa — fala connosco para perceberes qual o mais adequado ao teu caso.</p>',
 'publicado', 0, 1, DATE_SUB(NOW(), INTERVAL 17 DAY));
