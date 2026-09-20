<?php
/*
|--------------------------------------------------------------------------
| INDEX.PHP
|--------------------------------------------------------------------------
| Página inicial da Bitline Angola
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/funcoes.php';

$tituloPagina    = 'Soluções Inteligentes em Tecnologia da Informação';
$descricaoPagina = 'A Bitline Angola desenvolve soluções completas em Infraestrutura de Redes, Cibersegurança, Cloud Computing, Desenvolvimento de Software e Telecomunicações.';
$paginaAtiva     = 'inicio';

require_once __DIR__ . '/includes/header.php';

require_once __DIR__ . '/includes/home/hero.php';

require_once __DIR__ . '/includes/home/sobre.php';

require_once __DIR__ . '/includes/home/servicos.php';

require_once __DIR__ . '/includes/home/diferenciais.php';

require_once __DIR__ . '/includes/home/numeros.php';

require_once __DIR__ . '/includes/home/projetos.php';

require_once __DIR__ . '/includes/home/blog-destaque.php';

require_once __DIR__ . '/includes/home/contacto-home.php';

require_once __DIR__ . '/includes/footer.php';
