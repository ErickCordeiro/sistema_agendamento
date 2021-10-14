<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use Source\Core\Session;
use CoffeeCode\Router\Router;

$session = new Session();
$route = new Router(url(), ":");

/**
 * WEB ROUTES
 */
$route->namespace("Source\App");
$route->get("/", "Web:root");
$route->get("/painel", "Web:home");
$route->get("/lista-eventos", "Web:events");
$route->post("/agendar", "Web:scheduling");
$route->get("/remover-agendamento/{code}", "Web:removeScheduling");

/**FUNCIONARIOS */
$route->get("/funcionarios", "Web:collaborator");
$route->get("/novo-funcionario", "Web:newCollaborator");
$route->post("/novo-funcionario", "Web:newCollaborator");
$route->get("/editar-funcionario/{code}", "Web:editCollaborator");
$route->post("/editar-funcionario/{code}", "Web:editCollaborator");
$route->post("/remover-funcionario/{code}", "Web:removeCollaborator");

$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");
$route->get("/resetar/{code}", "Web:reset");
$route->post("/resetar", "Web:reset");
$route->get("/sair", "Web:logout");


/*** ADMIN ROUTES*/
$route->group("/admin");
$route->get("/", "Admin:root");
$route->get("/dashboard", "Admin:home");
$route->post("/marcar-agendamento", "Admin:scheduling");
$route->get("/list-events", "Admin:events");
$route->post("/altera-status", "Admin:alterStatus");
$route->get("/remover-agendamento/{code}", "Admin:removeScheduling");
$route->post("/list-collaborator", "Admin:listCollaborator");
$route->post("/infos-collaborator", "Admin:infosCollaborator");

/*** HORARIOS BLOQUEADOS */
$route->get("/horarios-bloqueados", "Admin:blocks");
$route->get("/novo-bloqueio", "Admin:newBlocks");
$route->post("/novo-bloqueio", "Admin:newBlocks");
$route->get("/editar-bloqueio/{code}", "Admin:editBlocks");
$route->post("/editar-bloqueio/{code}", "Admin:editBlocks");
$route->post("/remover-bloqueio/{code}", "Admin:removeBlocks");

/*** CLIENTES */
$route->get("/clientes", "Admin:clients");
$route->get("/novo-cliente", "Admin:newClients");
$route->post("/novo-cliente", "Admin:newClients");
$route->get("/editar-cliente/{code}", "Admin:editClients");
$route->post("/editar-cliente/{code}", "Admin:editClients");
$route->post("/remover-cliente/{code}", "Admin:removeClients");

/*** Colaboradores */
$route->get("/colaboradores", "Admin:collaborators");
$route->get("/novo-colaborador", "Admin:newCollaborator");
$route->post("/novo-colaborador", "Admin:newCollaborator");
$route->get("/editar-colaborador/{code}", "Admin:editCollaborator");
$route->post("/editar-colaborador/{code}", "Admin:editCollaborator");
$route->post("/remover-colaborador/{code}", "Admin:removeCollaborator");

/*** MEDICOS */
$route->get("/medicos", "Admin:doctors");
$route->get("/novo-medico", "Admin:newDoctor");
$route->post("/novo-medico", "Admin:newDoctor");
$route->get("/editar-medico/{code}", "Admin:editDoctor");
$route->post("/editar-medico/{code}", "Admin:editDoctor");
$route->post("/remover-medico/{code}", "Admin:removeDoctor");


/*** FILIAIS */
$route->get("/filiais", "Admin:branchs");
$route->get("/nova-filial", "Admin:newBranchs");
$route->post("/nova-filial", "Admin:newBranchs");
$route->get("/editar-filial/{code}", "Admin:editBranchs");
$route->post("/editar-filial/{code}", "Admin:editBranchs");
$route->post("/remover-filial/{code}", "Admin:removeBranchs");


/*** EXAMES */
$route->get("/exames", "Admin:exams");
$route->get("/novo-exame", "Admin:newExams");
$route->post("/novo-exame", "Admin:newExams");
$route->get("/editar-exame/{code}", "Admin:editExams");
$route->post("/editar-exame/{code}", "Admin:editExams");
$route->post("/remover-exame/{code}", "Admin:removeExams");

/*** COMPLEMENTARES */
$route->get("/complementares", "Admin:complementary");
$route->get("/novo-complementar", "Admin:newComplementary");
$route->post("/novo-complementar", "Admin:newComplementary");
$route->get("/editar-complementar/{code}", "Admin:editComplementary");
$route->post("/editar-complementar/{code}", "Admin:editComplementary");
$route->post("/remover-complementar/{code}", "Admin:removeComplementary");


$route->get("/login", "Admin:login");
$route->post("/login", "Admin:login");
$route->get("/sair", "Admin:logout");


/**
 * ERROR ROUTES
 */
$route->namespace("Source\App")->group("/ops");
$route->get("/{errcode}", "Web:error");

/**
 * ROUTE
 */
$route->dispatch();


/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}


ob_end_flush();