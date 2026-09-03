<?php
$cur_lang = $_SESSION['glpilanguage'] ?? 'en_GB';
$lang_prefix = substr($cur_lang, 0, 2); 
$LANG = [];

switch ($lang_prefix) {
    case 'pt':
        $LANG = [
            'page_title' => 'Meus Chamados', 'card_new' => 'Novos', 'card_process' => 'Em Atendimento', 'card_pending' => 'Pendentes', 'card_total' => 'Total',
            'list_title' => 'Lista Detalhada', 'col_id' => 'ID', 'col_entity' => 'Entidade', 'col_status' => 'Status', 'col_title' => 'Título / Categoria',
            'col_approv' => 'Aprov.', 'col_req' => 'Requerente', 'col_date' => 'Abertura', 'col_update' => 'Últ. Atualização', 'col_action' => 'Ação',
            'btn_check' => 'Checar', 'empty_title' => 'Tudo limpo!', 'empty_msg' => 'Nenhum chamado encontrado.', 'no_req' => '(Sem req.)',
            'st_new' => 'Novo', 'st_assign' => 'Em Atend.', 'st_plan' => 'Planejado', 'st_wait' => 'Pendente', 'st_solved' => 'Solucionado', 'st_closed' => 'Fechado',
            'filt_ent' => 'Filtrar Entidade', 'filt_st' => 'Filtrar Status', 'all' => 'Todos', 'interact' => 'Histórico e Interação', 
            'placeholder' => 'Digite seu novo acompanhamento aqui...', 'send' => 'Enviar', 'cancel' => 'Fechar',
            'view_my' => 'Meus Chamados', 'view_group' => 'Fila do Grupo', 'st_updated' => 'Atualizado!',
            'assign' => 'Assumir', 'solve' => 'Solucionar', 'solve_title' => 'Solucionar Chamado', 'solve_desc' => 'Descrição da Solução', 'new_reply' => 'Nova Resposta',
            'dt_search' => 'Pesquisar:', 'dt_length' => 'Mostrar _MENU_ registros', 'dt_info' => 'Mostrando de _START_ até _END_ de _TOTAL_ registros',
            'dt_empty' => 'Nenhum registro encontrado', 'dt_next' => 'Próximo', 'dt_prev' => 'Anterior'
        ];
        break;

    case 'es':
        $LANG = [
            'page_title' => 'Mis Casos', 'card_new' => 'Nuevos', 'card_process' => 'En Proceso', 'card_pending' => 'Pendientes', 'card_total' => 'Total',
            'list_title' => 'Lista Detallada', 'col_id' => 'ID', 'col_entity' => 'Entidad', 'col_status' => 'Estado', 'col_title' => 'Título / Categoría',
            'col_approv' => 'Aprob.', 'col_req' => 'Solicitante', 'col_date' => 'Apertura', 'col_update' => 'Últ. Actualización', 'col_action' => 'Acción',
            'btn_check' => 'Revisar', 'empty_title' => '¡Todo limpio!', 'empty_msg' => 'No hay tickets encontrados.', 'no_req' => '(Sin sol.)',
            'st_new' => 'Nuevo', 'st_assign' => 'En Proceso', 'st_plan' => 'Planificado', 'st_wait' => 'Pendiente', 'st_solved' => 'Resuelto', 'st_closed' => 'Cerrado',
            'filt_ent' => 'Filtrar Entidad', 'filt_st' => 'Filtrar Estado', 'all' => 'Todos', 'interact' => 'Historial e Interacción', 
            'placeholder' => 'Escriba su nuevo seguimiento aquí...', 'send' => 'Enviar', 'cancel' => 'Cerrar',
            'view_my' => 'Mis Casos', 'view_group' => 'Cola del Grupo', 'st_updated' => '¡Actualizado!',
            'assign' => 'Asignar', 'solve' => 'Resolver', 'solve_title' => 'Resolver Caso', 'solve_desc' => 'Descripción de la Solución', 'new_reply' => 'Nueva Respuesta',
            'dt_search' => 'Buscar:', 'dt_length' => 'Mostrar _MENU_ registros', 'dt_info' => 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            'dt_empty' => 'Ningún registro encontrado', 'dt_next' => 'Siguiente', 'dt_prev' => 'Anterior'
        ];
        break;
    
    case 'fr':
        $LANG = [
            'page_title' => 'Mes Tickets', 'card_new' => 'Nouveaux', 'card_process' => 'En cours', 'card_pending' => 'En attente', 'card_total' => 'Total',
            'list_title' => 'Liste Détaillée', 'col_id' => 'ID', 'col_entity' => 'Entité', 'col_status' => 'Statut', 'col_title' => 'Titre / Catégorie',
            'col_approv' => 'Approb.', 'col_req' => 'Demandeur', 'col_date' => 'Ouverture', 'col_update' => 'Dern. MàJ', 'col_action' => 'Action',
            'btn_check' => 'Vérifier', 'empty_title' => 'Tout est propre !', 'empty_msg' => 'Aucun ticket trouvé.', 'no_req' => '(Sans dem.)',
            'st_new' => 'Nouveau', 'st_assign' => 'En cours', 'st_plan' => 'Planifié', 'st_wait' => 'En attente', 'st_solved' => 'Résolu', 'st_closed' => 'Clos',
            'filt_ent' => 'Filtrer Entité', 'filt_st' => 'Filtrer Statut', 'all' => 'Tous', 'interact' => 'Historique et Interaction', 
            'placeholder' => 'Tapez votre nouveau suivi ici...', 'send' => 'Envoyer', 'cancel' => 'Fermer',
            'view_my' => 'Mes Tickets', 'view_group' => 'File du Groupe', 'st_updated' => 'Mis à jour !',
            'assign' => 'S\'assigner', 'solve' => 'Résoudre', 'solve_title' => 'Résoudre le Ticket', 'solve_desc' => 'Description de la Solution', 'new_reply' => 'Nouvelle Réponse',
            'dt_search' => 'Rechercher:', 'dt_length' => 'Afficher _MENU_ éléments', 'dt_info' => 'Affichage de _START_ à _END_ sur _TOTAL_ éléments',
            'dt_empty' => 'Aucune donnée disponible', 'dt_next' => 'Suivant', 'dt_prev' => 'Précédent'
        ];
        break;

    default: // INGLÊS /
        $LANG = [
            'page_title' => 'My Tickets', 'card_new' => 'New', 'card_process' => 'Processing', 'card_pending' => 'Pending', 'card_total' => 'Total',
            'list_title' => 'Detailed List', 'col_id' => 'ID', 'col_entity' => 'Entity', 'col_status' => 'Status', 'col_title' => 'Title / Category',
            'col_approv' => 'Approv.', 'col_req' => 'Requester', 'col_date' => 'Opened', 'col_update' => 'Last Update', 'col_action' => 'Action',
            'btn_check' => 'Check', 'empty_title' => 'All clean!', 'empty_msg' => 'No tickets found.', 'no_req' => '(No req.)',
            'st_new' => 'New', 'st_assign' => 'Processing', 'st_plan' => 'Planned', 'st_wait' => 'Pending', 'st_solved' => 'Solved', 'st_closed' => 'Closed',
            'filt_ent' => 'Filter Entity', 'filt_st' => 'Filter Status', 'all' => 'All', 'interact' => 'History & Interaction', 
            'placeholder' => 'Type your new follow-up here...', 'send' => 'Send', 'cancel' => 'Close',
            'view_my' => 'My Tickets', 'view_group' => 'Group Queue', 'st_updated' => 'Updated!',
            'assign' => 'Assign', 'solve' => 'Solve', 'solve_title' => 'Solve Ticket', 'solve_desc' => 'Solution Description', 'new_reply' => 'New Reply',
            'dt_search' => 'Search:', 'dt_length' => 'Show _MENU_ entries', 'dt_info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
            'dt_empty' => 'No data available', 'dt_next' => 'Next', 'dt_prev' => 'Previous'
        ];
        break;
}