<?php

$cur_lang = $_SESSION['glpilanguage'] ?? 'en_GB';
$LANG = [];

if ($cur_lang === 'pt_BR') {
    // pt-br
    $LANG = [
        'page_title'   => 'Meus Chamados',
        'card_new'     => 'Novos',
        'card_process' => 'Em Atendimento',
        'card_pending' => 'Pendentes',
        'card_total'   => 'Total',
        'list_title'   => 'Lista Detalhada - Chamados atribuídos a mim',
        'col_id'       => 'ID',
        'col_status'   => 'Status',
        'col_entity'   => 'Entidade', 
        'col_title'    => 'Título / Categoria',
        'col_approv'   => 'Aprov.',
        'col_req'      => 'Requerente',
        'col_date'     => 'Abertura',
        'col_update'   => 'Últ. Atualização',
        'col_action'   => 'Ação',
        'btn_check'    => 'Checar',
        'empty_title'  => 'Tudo limpo!',
        'empty_msg'    => 'Nenhum chamado atribuido a você.',
        'no_req'       => '(Sem req.)',
        'st_new'       => 'Novo',
        'st_assign'    => 'Em Atend.',
        'st_plan'      => 'Planejado',
        'st_wait'      => 'Pendente',
        'st_solved'    => 'Solucionado',
        'st_closed'    => 'Fechado'
    ];
} else {
    // Ingles
    $LANG = [
        'page_title'   => 'My Tickets',
        'card_new'     => 'New',
        'card_process' => 'Processing',
        'card_pending' => 'Pending',
        'card_total'   => 'Total',
        'list_title'   => 'Detailed List - Calls assigned to me',
        'col_id'       => 'ID',
        'col_status'   => 'Status',
        'col_title'    => 'Title / Category',
        'col_entity'   => 'Entity', 
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
}