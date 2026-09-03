<?php
ini_set('display_errors', 1); error_reporting(E_ALL);
include ('../../../inc/includes.php');
Session::checkLoginUser();


// chamado assumir
if (isset($_POST['action']) && $_POST['action'] == 'assign_ticket' && isset($_POST['ticket_id'])) {
    Session::checkCSRF(); 
    $tu = new Ticket_User();
    $input = ['tickets_id' => (int)$_POST['ticket_id'], 'users_id' => Session::getLoginUserID(), 'type' => 2];
    if ($tu->add($input)) { echo "ok"; } else { echo "error"; }
    exit;
}

// solucao salva
if (isset($_POST['action']) && $_POST['action'] == 'solve_ticket' && isset($_POST['items_id'])) {
    Session::checkCSRF(); 
    $sol = new ITILSolution();
    $input = ['itemtype' => 'Ticket', 'items_id' => (int)$_POST['items_id'], 'content' => $_POST['content'], 'users_id' => Session::getLoginUserID()];
    if ($sol->add($input)) { echo "ok"; } else { echo "error"; }
    exit;
}

// acompanhamento sallvar
if (isset($_POST['action']) && $_POST['action'] == 'add_followup' && isset($_POST['items_id'])) {
    Session::checkCSRF(); // [SEGURANÇA: Anti-CSRF]
    $fup = new ITILFollowup();
    $input = ['itemtype' => 'Ticket', 'items_id' => (int)$_POST['items_id'], 'content' => $_POST['content'], 'users_id' => Session::getLoginUserID(), 'is_private' => 0];
    if ($fup->add($input)) { echo "ok"; } else { echo "error"; }
    exit;
}

// . mudar statuss
if (isset($_POST['action']) && $_POST['action'] == 'update_status' && isset($_POST['ticket_id']) && isset($_POST['status'])) {
    Session::checkCSRF(); 
    $ticket = new Ticket();
    $input = ['id' => (int)$_POST['ticket_id'], 'status' => (int)$_POST['status']];
    if ($ticket->update($input)) { echo "ok"; } else { echo "error"; }
    exit;
}

// . historico
if (isset($_GET['action']) && $_GET['action'] == 'get_history' && isset($_GET['id'])) {
    $t_id = (int)$_GET['id']; global $DB; $html = "";
    $ticket_iter = $DB->request(['FROM' => 'glpi_tickets', 'WHERE' => ['id' => $t_id]]);
    if (count($ticket_iter)) {
        $ticket = $ticket_iter->current(); 
        $desc = html_entity_decode($ticket['content'] ?? '');
        $html .= "<div class='alert alert-secondary mb-3' style='font-size: 0.9em; border-left: 4px solid #6c757d;'><strong><i class='fas fa-align-left'></i> Descrição Original:</strong><div class='mt-2' style='max-height: 200px; overflow-y: auto;'>".$desc."</div></div>";
    }
    $f_iter = $DB->request(['SELECT' => ['glpi_itilfollowups.content', 'glpi_itilfollowups.date_creation', 'glpi_users.firstname', 'glpi_users.realname', 'glpi_users.name'], 'FROM' => 'glpi_itilfollowups', 'LEFT JOIN' => ['glpi_users' => ['ON' => ['glpi_itilfollowups' => 'users_id', 'glpi_users' => 'id']]], 'WHERE' => ['items_id' => $t_id, 'itemtype' => 'Ticket'], 'ORDER' => 'date_creation ASC']);
    if (count($f_iter)) {
        $html .= "<h6 class='mt-3 mb-2 font-weight-bold'><i class='fas fa-comments text-primary'></i> Histórico de Conversa:</h6>";
        foreach ($f_iter as $f) {
            $u_name = trim($f['firstname'] . ' ' . $f['realname']); if (empty($u_name)) $u_name = $f['name'];
            
            
            $u_name = htmlspecialchars($u_name, ENT_QUOTES, 'UTF-8');
            $content = html_entity_decode($f['content'] ?? '');
            $date_f = date("d/m/Y H:i", strtotime($f['date_creation'])); 
            
            $html .= "<div class='card mb-2 shadow-sm'><div class='card-header py-1 bg-light text-muted' style='font-size:0.85em;'><strong><i class='fas fa-user-circle'></i> $u_name</strong> <span class='float-end'>$date_f</span></div><div class='card-body py-2' style='font-size:0.9em;'>$content</div></div>";
        }
    } else { $html .= "<p class='text-muted text-center mt-3'><i class='fas fa-info-circle'></i> Nenhum comentário adicionado.</p>"; }
    echo $html; exit; 
}
// ==

include_once('../inc/languages.php');
Html::header($LANG['page_title'], $_SERVER['PHP_SELF'], "helpdesk", "techview");

echo '<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">';
echo "<style>
    .card-stats { border: none; border-radius: 8px; color: white; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .card-stats h2 { font-size: 2.5rem; margin: 0; font-weight: bold; }
    .card-stats span { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
    .bg-gradient-blue { background: linear-gradient(45deg, #818181, #bebebe); }
    .bg-gradient-green { background: linear-gradient(45deg, #2ed8b6, #59e0c5); }
    .bg-gradient-orange { background: linear-gradient(45deg, #FFB64D, #ffcb80); }
    .bg-gradient-red { background: linear-gradient(45deg, #3fb638, #67b962); }
    .badge-sla-danger { background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; display: inline-block; margin-top: 5px; }
    .badge-sla-warning { background-color: #ffc107; color: #212529; padding: 4px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; display: inline-block; margin-top: 5px; }
    .badge-sla-ok { background-color: #198754; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; display: inline-block; margin-top: 5px; }
    .badge-sla-none { background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; display: inline-block; margin-top: 5px; }
    .ticket-link { color: var(--text-color) !important; text-decoration: none; }
    .ticket-link:hover { color: #007bff !important; }
    .ticket-sub { color: var(--text-muted) !important; }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { margin-bottom: 15px; padding: 0 15px; }
    .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { margin-top: 15px; padding: 0 15px 15px 15px; }
    .history-scrollbox { max-height: 50vh; overflow-y: auto; padding-right: 5px; margin-bottom: 15px; }
    .status-ajax-select { font-size: 0.85em; font-weight: 500; cursor: pointer; border: 1px solid #ced4da; transition: all 0.3s ease; width: auto; display: inline-block; min-width: 130px; }
    .status-ajax-select:focus { box-shadow: none; border-color: #80bdff; }
    
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
    .pulse-alert { border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite; }
</style>";

echo "<div class='m-3'>";
try {
    global $DB;
    $myID = Session::getLoginUserID();
    $view = $_GET['view'] ?? 'my';
    
    $my_groups = [];
    $group_iter = $DB->request(['FROM' => 'glpi_groups_users', 'WHERE' => ['users_id' => $myID]]);
    foreach ($group_iter as $g) { $my_groups[] = $g['groups_id']; }

    $criteria = [
        'SELECT' => [
            'glpi_tickets.id', 'glpi_tickets.name', 'glpi_tickets.date', 'glpi_tickets.date_mod', 
            'glpi_tickets.status', 'glpi_tickets.time_to_resolve',
            'glpi_itilcategories.completename AS cat_name', 'glpi_entities.completename AS entity_name'
        ],
        'FROM' => 'glpi_tickets',
        'LEFT JOIN' => [
            'glpi_itilcategories' => ['ON' => ['glpi_tickets' => 'itilcategories_id', 'glpi_itilcategories' => 'id']],
            'glpi_entities'       => ['ON' => ['glpi_tickets' => 'entities_id', 'glpi_entities' => 'id']]
        ],
        'WHERE' => ['glpi_tickets.status' => ['NOT IN', [5, 6]], 'glpi_tickets.is_deleted' => 0],
        'ORDER' => "glpi_tickets.date DESC"
    ];

    if ($view == 'group' && count($my_groups) > 0) {
        $criteria['INNER JOIN'] = ['glpi_groups_tickets' => ['ON' => ['glpi_groups_tickets' => 'tickets_id', 'glpi_tickets' => 'id']]];
        $criteria['WHERE']['glpi_groups_tickets.type'] = 2; 
        $criteria['WHERE']['glpi_groups_tickets.groups_id'] = $my_groups;
    } else {
        $view = 'my';
        $criteria['INNER JOIN'] = ['glpi_tickets_users' => ['ON' => ['glpi_tickets_users' => 'tickets_id', 'glpi_tickets' => 'id']]];
        $criteria['WHERE']['glpi_tickets_users.type'] = 2;
        $criteria['WHERE']['glpi_tickets_users.users_id'] = $myID;
    }
    
    $iterator = $DB->request($criteria);
    $tickets = iterator_to_array($iterator);
    $count_total = count($tickets); $count_novo = 0; $count_atendimento = 0; $count_pendente = 0;
    
    $unique_entities = []; $unique_statuses = [];
    $status_map = [ 1 => $LANG['st_new'], 2 => $LANG['st_assign'], 3 => $LANG['st_plan'], 4 => $LANG['st_wait'], 5 => $LANG['st_solved'], 6 => $LANG['st_closed'] ];

    foreach ($tickets as $t) {
        $st = $t['status'];
        if ($st == 1) $count_novo++; if ($st == 2 || $st == 3) $count_atendimento++; if ($st == 4) $count_pendente++;
        $st_text = $status_map[$st] ?? $st; $unique_statuses[$st_text] = $st_text;
        
        
        $ent_raw = !empty($t['entity_name']) ? $t['entity_name'] : '-'; 
        $ent = htmlspecialchars($ent_raw, ENT_QUOTES, 'UTF-8');
        $unique_entities[$ent] = $ent;
    }
    ksort($unique_entities); ksort($unique_statuses);

    echo "<div class='d-flex justify-content-between align-items-center mb-3 mt-1'>";
    echo "<h4 class='m-0 font-weight-bold' style='color: var(--text-color);'><i class='fas fa-exchange-alt text-primary'></i> TechView</h4>";
    echo "<div class='btn-group shadow-sm bg-white'>";
    $btn_my = ($view == 'my') ? 'btn-primary text-white' : 'btn-outline-primary';
    $btn_gp = ($view == 'group') ? 'btn-primary text-white' : 'btn-outline-primary';
    echo "<a href='?view=my' class='btn $btn_my btn-sm'><i class='fas fa-user'></i> {$LANG['view_my']}</a>";
    if (count($my_groups) > 0) { echo "<a href='?view=group' class='btn $btn_gp btn-sm'><i class='fas fa-users'></i> {$LANG['view_group']}</a>"; } 
    else { echo "<button class='btn btn-outline-secondary btn-sm disabled'><i class='fas fa-users'></i> {$LANG['view_group']} (Sem Grupos)</button>"; }
    echo "</div></div>";

    echo "<div class='row'>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-red'><h2>{$count_novo}</h2><span><i class='fas fa-star'></i> {$LANG['card_new']}</span></div></div>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-green'><h2>{$count_atendimento}</h2><span><i class='fas fa-tools'></i> {$LANG['card_process']}</span></div></div>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-orange'><h2>{$count_pendente}</h2><span><i class='fas fa-pause-circle'></i> {$LANG['card_pending']}</span></div></div>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-blue'><h2>{$count_total}</h2><span><i class='fas fa-list'></i> {$LANG['card_total']}</span></div></div>";
    echo "</div>";

    echo "<div class='card border-primary'>";
    echo "<div class='card-header' style='background-color: #343a40; color: white;'><h3 class='card-title'><i class='fas fa-list-alt'></i> {$LANG['list_title']}</h3></div>";
    
    echo "<div class='row mb-2 mx-2 mt-3'>
            <div class='col-md-4'>
                <label class='text-muted small fw-bold mb-1'><i class='fas fa-building'></i> {$LANG['filt_ent']}</label>
                <select id='filter_entity' class='form-select form-select-sm'><option value=''>-- {$LANG['all']} --</option>";
                foreach ($unique_entities as $ue) { echo "<option value='{$ue}'>{$ue}</option>"; }
    echo "      </select></div>
            <div class='col-md-3'>
                <label class='text-muted small fw-bold mb-1'><i class='fas fa-info-circle'></i> {$LANG['filt_st']}</label>
                <select id='filter_status' class='form-select form-select-sm'><option value=''>-- {$LANG['all']} --</option>";
                foreach ($unique_statuses as $us) { echo "<option value='{$us}'>{$us}</option>"; }
    echo "      </select></div>
          </div><hr class='m-0'>";

    echo "<div class='card-body p-0 pt-3'>"; 

    if ($count_total > 0) {
        echo "<table id='techviewTable' class='table table-striped table-hover m-0 w-100'>";
        echo "<thead class='thead-light'><tr>
                <th style='width: 5%'>{$LANG['col_id']}</th>
                <th style='width: 12%'>{$LANG['col_entity']}</th>
                <th style='width: 13%'>{$LANG['col_status']}</th>
                <th>{$LANG['col_title']}</th>
                <th style='width: 14%'>{$LANG['col_req']}</th>
                <th style='width: 10%'>{$LANG['col_date']}</th>
                <th style='width: 10%'>{$LANG['col_update']}</th>
                <th style='width: 14%' class='text-center'>{$LANG['col_action']}</th>
              </tr></thead><tbody>";

        foreach ($tickets as $data) {
            $link = "../../../front/ticket.form.php?id=" . $data['id'];
            
            //  
            $categoria_raw = !empty($data['cat_name']) ? $data['cat_name'] : '-';
            $categoria = htmlspecialchars($categoria_raw, ENT_QUOTES, 'UTF-8');
            $entity_text_raw = !empty($data['entity_name']) ? $data['entity_name'] : '-';
            $entity_text = htmlspecialchars($entity_text_raw, ENT_QUOTES, 'UTF-8');
            $ticket_name_clean = htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8');
            
            $ts_open = strtotime($data['date']);
            $date_open_br = "<strong>" . date("d/m/Y", $ts_open) . "</strong><br>" . date("H:i", $ts_open);

            $st_dropdown = "<select class='form-select form-select-sm status-ajax-select' data-ticket-id='{$data['id']}'>";
            foreach ($status_map as $st_id => $st_name) {
                $sel = ($data['status'] == $st_id) ? "selected" : "";
                $st_dropdown .= "<option value='{$st_id}' {$sel}>{$st_name}</option>";
            }
            $st_dropdown .= "</select>";

            // sla
            $sla_badge = "";
            if (!empty($data['time_to_resolve']) && $data['time_to_resolve'] != '0000-00-00 00:00:00') {
                $now = time(); $ttr = strtotime($data['time_to_resolve']); $diff = $ttr - $now; $ttr_br = date("d/m/Y H:i", $ttr);
                if ($diff < 0) { $sla_badge = "<div class='badge-sla-danger'><i class='fas fa-fire'></i> SLA: $ttr_br</div>"; }
                elseif ($diff < 7200) { $sla_badge = "<div class='badge-sla-warning'><i class='fas fa-clock'></i> SLA: $ttr_br</div>"; }
                else { $sla_badge = "<div class='badge-sla-ok'><i class='fas fa-check-circle'></i> SLA: $ttr_br</div>"; }
            } else { $sla_badge = "<div class='badge-sla-none'><i class='fas fa-minus-circle'></i> Sem SLA</div>"; }

            
            $followup_iter = $DB->request(['SELECT' => ['users_id', 'date_creation'], 'FROM' => 'glpi_itilfollowups', 'WHERE' => ['items_id' => $data['id'], 'itemtype' => 'Ticket'], 'ORDER' => 'date_creation DESC', 'LIMIT' => 1]);
            $last_update_br = "<span class='text-muted small'>-</span>"; $raw_last_update = $data['date_mod'];
            $new_reply_alert = "";
            if (count($followup_iter) > 0) {
                $frow = $followup_iter->current(); $raw_last_update = $frow['date_creation'];
                $last_update_br = "<strong>" . date("d/m/Y", strtotime($frow['date_creation'])) . "</strong><br>" . date("H:i", strtotime($frow['date_creation']));
                
                if ($frow['users_id'] != $myID) {
                    $new_reply_alert = " <span class='badge bg-danger pulse-alert ms-1' title='{$LANG['new_reply']}'><i class='fas fa-bell'></i></span>";
                }
            } else {
                if ($data['date_mod'] > $data['date']) { $last_update_br = "<strong>" . date("d/m/Y", strtotime($data['date_mod'])) . "</strong><br>" . date("H:i", strtotime($data['date_mod'])); }
            }

            
            $req_iter = $DB->request(['SELECT' => ['glpi_users.firstname', 'glpi_users.realname', 'glpi_users.name'], 'FROM' => 'glpi_tickets_users', 'INNER JOIN' => ['glpi_users' => ['ON' => ['glpi_tickets_users' => 'users_id', 'glpi_users' => 'id']]], 'WHERE' => ['glpi_tickets_users.tickets_id' => $data['id'], 'glpi_tickets_users.type' => 1], 'LIMIT' => 1]);
            $r_name = "<i>{$LANG['no_req']}</i>";
            if (count($req_iter) > 0) {
                $r = $req_iter->current(); $full = trim($r['firstname'] . ' ' . $r['realname']); 
                $raw_r_name = empty($full) ? $r['name'] : $full;
                
                $r_name = htmlspecialchars($raw_r_name, ENT_QUOTES, 'UTF-8');
            }

            
            $is_mine = count($DB->request(['FROM'=>'glpi_tickets_users', 'WHERE'=>['tickets_id'=>$data['id'], 'users_id'=>$myID, 'type'=>2]])) > 0;

            echo "<tr>";
            echo "<td class='font-weight-bold align-middle' data-order='{$data['id']}'>#{$data['id']}</td>";
            echo "<td style='font-size:0.85em; font-weight:500;' class='ticket-sub align-middle'>{$entity_text}</td>";
            echo "<td class='align-middle' data-order='{$status_map[$data['status']]}'>{$st_dropdown}</td>";
            echo "<td class='align-middle'>
                    <div style='font-weight:bold;'><a href='$link' class='ticket-link'>{$ticket_name_clean}{$new_reply_alert}</a></div>
                    <div style='font-size:0.85em;' class='ticket-sub'><i class='fas fa-folder-open'></i> {$categoria}</div>
                    {$sla_badge}
                  </td>";
            echo "<td class='align-middle'><i class='fas fa-user text-muted mr-1'></i> {$r_name}</td>";
            echo "<td style='font-size:0.9em;' class='align-middle' data-order='{$data['date']}'>{$date_open_br}</td>";
            echo "<td style='font-size:0.9em;' class='align-middle' data-order='{$raw_last_update}'>{$last_update_br}</td>";
            
            
            echo "<td class='text-center align-middle'>
                    <div class='d-flex justify-content-center' style='gap: 4px;'>";
            
            
            if (!$is_mine) {
                echo "<button type='button' class='btn btn-secondary btn-sm text-white btn-assign' data-ticket-id='{$data['id']}' title='{$LANG['assign']}'>
                        <i class='fas fa-hand-paper'></i>
                      </button>";
            }

            // botao Interagir
            echo "      <button type='button' class='btn btn-primary btn-sm text-white' data-bs-toggle='modal' data-bs-target='#modalInteract' data-ticket-id='{$data['id']}' title='{$LANG['interact']}'>
                            <i class='fas fa-comments'></i>
                        </button>";
            
            // . solucionar rapido 
            echo "      <button type='button' class='btn btn-success btn-sm text-white' data-bs-toggle='modal' data-bs-target='#modalSolve' data-ticket-id='{$data['id']}' title='{$LANG['solve']}'>
                            <i class='fas fa-check'></i>
                        </button>";
            
            // linkk externo 
            echo "      <a href='$link' class='btn btn-warning btn-sm text-dark' title='{$LANG['btn_check']}'>
                            <i class='fas fa-external-link-alt'></i>
                        </a>
                    </div>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='p-5 text-center text-muted'><h4>{$LANG['empty_title']}</h4><p>{$LANG['empty_msg']}</p></div>";
    }
    echo "</div></div>"; 
} catch (\Throwable $e) { echo "<div class='alert alert-danger m-3'><h4>🚨 Error:</h4>" . $e->getMessage() . "</div>"; }
echo "</div>"; 


?>
<div class="modal fade" id="modalInteract" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-comments text-primary"></i> <?php echo $LANG['interact']; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div id="historyContent" class="history-scrollbox"></div>
        <hr class="my-3">
        <input type="hidden" id="interact_ticket_id" value="">
        <div class="mb-2"><textarea id="new_followup_content" class="form-control bg-white" rows="3" placeholder="<?php echo $LANG['placeholder']; ?>"></textarea></div>
        <div class="text-end">
            <button type="button" class="btn btn-primary px-4" id="btnSaveFollowup"><i class="fas fa-paper-plane"></i> <?php echo $LANG['send']; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>

 
<div class="modal fade" id="modalSolve" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-success">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle"></i> <?php echo $LANG['solve_title']; ?></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" id="solve_ticket_id" value="">
        <div class="mb-2">
            <label class="form-label font-weight-bold"><?php echo $LANG['solve_desc']; ?></label>
            <textarea id="solve_content" class="form-control bg-white" rows="4" required></textarea>
        </div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $LANG['cancel']; ?></button>
            <button type="button" class="btn btn-success px-4" id="btnSaveSolve"><i class="fas fa-save"></i> <?php echo $LANG['solve']; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php


$csrf_token = Session::getNewCSRFToken();

echo "<script src='https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js'></script>";
echo "<script src='https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js'></script>";
echo "<script>
var csrfToken = '{$csrf_token}';

$(document).ready(function() {
    var table = $('#techviewTable').DataTable({
        'language': {
            'search': '{$LANG['dt_search']}', 'lengthMenu': '{$LANG['dt_length']}', 'info': '{$LANG['dt_info']}',
            'emptyTable': '{$LANG['dt_empty']}', 'infoEmpty': '{$LANG['dt_empty']}', 'zeroRecords': '{$LANG['dt_empty']}',
            'paginate': { 'next': '{$LANG['dt_next']}', 'previous': '{$LANG['dt_prev']}' }
        },
        'order': [[ 5, 'desc' ]], 'pageLength': 15, 'lengthMenu': [10, 15, 25, 50],
        'columnDefs': [ { 'orderable': false, 'targets': 7 } ]
    });

    $('#filter_entity').on('change', function() { table.column(1).search(this.value).draw(); });
    $('#filter_status').on('change', function() { table.column(2).search(this.value).draw(); });

    $('#techviewTable tbody').on('change', '.status-ajax-select', function() {
        var selectBox = $(this); var tId = selectBox.data('ticket-id'); var newSt = selectBox.val();
        selectBox.prop('disabled', true); 
        // [SEGURANÇA: Anti-CSRF] Token adicionado ao payload
        $.post('main.php', { action: 'update_status', ticket_id: tId, status: newSt, _glpi_csrf_token: csrfToken }, function(res) {
            if(res.trim() === 'ok') {
                selectBox.addClass('is-valid border-success text-success').prop('disabled', false);
                setTimeout(function() { selectBox.removeClass('is-valid border-success text-success'); }, 2000);
            } else { alert('Erro ao atualizar status.'); selectBox.prop('disabled', false); }
        });
    });

    // 1. ASSUMIR CHAMADO (Mãozinha)
    $('#techviewTable tbody').on('click', '.btn-assign', function() {
        var btn = $(this); var tId = btn.data('ticket-id');
        btn.prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i>');
        // [SEGURANÇA: Anti-CSRF] Token adicionado ao payload
        $.post('main.php', { action: 'assign_ticket', ticket_id: tId, _glpi_csrf_token: csrfToken }, function(res) {
            if(res.trim() === 'ok') { location.reload(); } // Recarrega para limpar da fila de grupo
            else { alert('Erro ao assumir.'); btn.prop('disabled', false); }
        });
    });

    // 2. SOLUÇÃO RÁPIDA (Modal)
    $('#modalSolve').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); var tId = button.data('ticket-id'); var modal = $(this);
        modal.find('#solve_ticket_id').val(tId); modal.find('#solve_content').val('');
    });
    
    $('#btnSaveSolve').on('click', function() {
        var content = $('#solve_content').val(); var t_id = $('#solve_ticket_id').val(); var btn = $(this);
        if (content.trim() === '') { $('#solve_content').focus(); return; }
        btn.prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i>...');
        // [SEGURANÇA: Anti-CSRF] Token adicionado ao payload
        $.post('main.php', { action: 'solve_ticket', items_id: t_id, content: content, _glpi_csrf_token: csrfToken }, function(res) {
            if(res.trim() === 'ok') { location.reload(); } // Recarrega para tirar da fila (Solucionados não aparecem)
            else { alert('Erro ao salvar solução.'); btn.prop('disabled', false).html('<i class=\"fas fa-save\"></i> {$LANG['solve']}'); }
        });
    });

    // INTERAÇÃO (Original)
    function loadHistory(ticketId) {
        var box = $('#historyContent'); box.html('<div class=\"text-center my-4\"><i class=\"fas fa-spinner fa-spin fa-2x text-primary\"></i></div>');
        $.get('main.php?action=get_history&id=' + ticketId, function(data) { box.html(data); box.scrollTop(box[0].scrollHeight); });
    }
    $('#modalInteract').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); var ticketId = button.data('ticket-id'); var modal = $(this);
        modal.find('#interact_ticket_id').val(ticketId); modal.find('.modal-title').html('<i class=\"fas fa-comments text-primary\"></i> {$LANG['interact']} - #' + ticketId);
        $('#new_followup_content').val(''); loadHistory(ticketId);
    });
    $('#btnSaveFollowup').on('click', function() {
        var content = $('#new_followup_content').val(); var t_id = $('#interact_ticket_id').val(); var btn = $(this);
        if (content.trim() === '') { $('#new_followup_content').focus(); return; }
        btn.prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i>...');
        // [SEGURANÇA: Anti-CSRF] Token adicionado ao payload
        $.post('main.php', { action: 'add_followup', items_id: t_id, content: content, _glpi_csrf_token: csrfToken }, function(res) {
            $('#new_followup_content').val(''); btn.prop('disabled', false).html('<i class=\"fas fa-paper-plane\"></i> {$LANG['send']}'); loadHistory(t_id);
        });
    });
});
</script>";
Html::footer();