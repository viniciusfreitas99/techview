<?php

$cur_lang = $_SESSION['glpilanguage'] ?? 'en_GB';
// Pega apenas as duas primeiras letras (ex: 'pt_BR' vira 'pt')
$lang_prefix = substr($cur_lang, 0, 2); 

$LANG = [];

switch ($lang_prefix) {
    case 'pt': // --- PORTUGUÊS ---
        $LANG = [
            'page_title'   => 'Meus Chamados',
            'card_new'     => 'Novos',
            'card_process' => 'Em Atendimento',
            'card_pending' => 'Pendentes',
            'card_total'   => 'Total',
            'list_title'   => 'Lista Detalhada - Chamados atribuídos a mim',
            'col_id'       => 'ID',
            'col_entity'   => 'Entidade',
            'col_status'   => 'Status',
            'col_title'    => 'Título / Categoria',
            'col_approv'   => 'Aprov.',
            'col_req'      => 'Requerente',
            'col_date'     => 'Abertura',
            'col_update'   => 'Últ. Atualização',
            'col_action'   => 'Ação',
            'btn_check'    => 'Checar',
            'empty_title'  => 'Tudo limpo!',
            'empty_msg'    => 'Nenhum chamado pendente.',
            'no_req'       => '(Sem req.)',
            'st_new'       => 'Novo',
            'st_assign'    => 'Em Atend.',
            'st_plan'      => 'Planejado',
            'st_wait'      => 'Pendente',
            'st_solved'    => 'Solucionado',
            'st_closed'    => 'Fechado'
        ];
        break;

    case 'es': // --- ESPANHOL ---
        $LANG = [
            'page_title'   => 'Mis Casos',
            'card_new'     => 'Nuevos',
            'card_process' => 'En Proceso',
            'card_pending' => 'Pendientes',
            'card_total'   => 'Total',
            'list_title'   => 'Lista Detallada',
            'col_id'       => 'ID',
            'col_entity'   => 'Entidad',
            'col_status'   => 'Estado',
            'col_title'    => 'Título / Categoría',
            'col_approv'   => 'Aprob.',
            'col_req'      => 'Solicitante',
            'col_date'     => 'Apertura',
            'col_update'   => 'Últ. Actualización',
            'col_action'   => 'Acción',
            'btn_check'    => 'Revisar',
            'empty_title'  => '¡Todo limpio!',
            'empty_msg'    => 'No hay tickets pendientes.',
            'no_req'       => '(Sin sol.)',
            'st_new'       => 'Nuevo',
            'st_assign'    => 'En Proceso',
            'st_plan'      => 'Planificado',
            'st_wait'      => 'Pendiente',
            'st_solved'    => 'Resuelto',
            'st_closed'    => 'Cerrado'
        ];
        break;

    case 'fr': // --- FRANCÊS ---
        $LANG = [
            'page_title'   => 'Mes Tickets',
            'card_new'     => 'Nouveaux',
            'card_process' => 'En cours',
            'card_pending' => 'En attente',
            'card_total'   => 'Total',
            'list_title'   => 'Liste Détaillée',
            'col_id'       => 'ID',
            'col_entity'   => 'Entité',
            'col_status'   => 'Statut',
            'col_title'    => 'Titre / Catégorie',
            'col_approv'   => 'Approb.',
            'col_req'      => 'Demandeur',
            'col_date'     => 'Ouverture',
            'col_update'   => 'Dernière MàJ',
            'col_action'   => 'Action',
            'btn_check'    => 'Vérifier',
            'empty_title'  => 'Tout est propre !',
            'empty_msg'    => 'Aucun ticket en attente.',
            'no_req'       => '(Sans dem.)',
            'st_new'       => 'Nouveau',
            'st_assign'    => 'En cours',
            'st_plan'      => 'Planifié',
            'st_wait'      => 'En attente',
            'st_solved'    => 'Résolu',
            'st_closed'    => 'Clos'
        ];
        break;

    default: // --- INGLÊS (Padrão para qualquer outro idioma) ---
        $LANG = [
            'page_title'   => 'My Tickets',
            'card_new'     => 'New',
            'card_process' => 'Processing',
            'card_pending' => 'Pending',
            'card_total'   => 'Total',
            'list_title'   => 'Detailed List - Calls assigned to me',
            'col_id'       => 'ID',
            'col_entity'   => 'Entity',
            'col_status'   => 'Status',
            'col_title'    => 'Title / Category',
            'col_approv'   => 'Approv.',
            'col_req'      => 'Requester',
            'col_date'     => 'Opened',
            'col_update'   => 'Last Update',
            'col_action'   => 'Action',
            'btn_check'    => 'Check',
            'empty_title'  => 'All clean!',
            'empty_msg'    => 'No pending tickets found.',
            'no_req'       => '(No req.)',
            'st_new'       => 'New',
            'st_assign'    => 'Processing',
            'st_plan'      => 'Planned',
            'st_wait'      => 'Pending',
            'st_solved'    => 'Solved',
            'st_closed'    => 'Closed'
        ];
        break;
}