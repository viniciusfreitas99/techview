<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include ('../../../inc/includes.php');
Session::checkLoginUser();

include_once('../inc/languages.php');

// ORDENAçao
$sort_col = isset($_GET['sort']) ? $_GET['sort'] : 'date';
$sort_ord = isset($_GET['order']) ? $_GET['order'] : 'DESC';

$allowed_cols = [
    'id'       => 'glpi_tickets.id', 
    'entity'   => 'glpi_entities.completename',
    'status'   => 'glpi_tickets.status', 
    'name'     => 'glpi_tickets.name', 
    'date'     => 'glpi_tickets.date', 
    'date_mod' => 'glpi_tickets.date_mod'
];

if (!array_key_exists($sort_col, $allowed_cols)) $sort_col = 'date';
if ($sort_ord !== 'ASC' && $sort_ord !== 'DESC') $sort_ord = 'DESC';
$db_order_field = $allowed_cols[$sort_col];

function getSortLink($col, $label, $current_col, $current_ord) {
    $new_ord = ($current_col == $col && $current_ord == 'DESC') ? 'ASC' : 'DESC';
    $icon = 'fa-sort text-muted';
    if ($current_col == $col) $icon = ($current_ord == 'ASC') ? 'fa-sort-up' : 'fa-sort-down';
    return "<a href='?sort=$col&order=$new_ord' style='text-decoration:none; color: var(--text-color);'>$label <i class='fas $icon ml-1'></i></a>";
}

Html::header($LANG['page_title'], $_SERVER['PHP_SELF'], "helpdesk", "techview");

// --- CSS ---
echo "<style>
    /* Cards (Mantém fixo pois tem fundo colorido) */
    .card-stats { border: none; border-radius: 8px; color: white; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .card-stats h2 { font-size: 2.5rem; margin: 0; font-weight: bold; }
    .card-stats span { font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }
    .bg-gradient-blue { background: linear-gradient(45deg, #818181, #bebebe); }
    .bg-gradient-green { background: linear-gradient(45deg, #2ed8b6, #59e0c5); }
    .bg-gradient-orange { background: linear-gradient(45deg, #FFB64D, #ffcb80); }
    .bg-gradient-red { background: linear-gradient(45deg, #3fb638, #67b962); }
    
    /* Badges */
    .badge-aprov-waiting { background-color: #ffc107; color: #212529; padding: 5px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; }
    .badge-aprov-ok { background-color: #28a745; color: white; padding: 5px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; }
    .badge-aprov-no { background-color: #dc3545; color: white; padding: 5px 8px; border-radius: 4px; font-size: 0.75em; font-weight: bold; }
    
    /* Links da Tabela no Hover */
    th a:hover { color: #007bff !important; }

    /* Correção para DARK MODE - Força cor adaptativa nos links de título */
    .ticket-link { color: var(--text-color) !important; }
    .ticket-sub { color: var(--text-muted) !important; }
</style>";

echo "<div class='m-3'>";

try {
    global $DB;
    $myID = Session::getLoginUserID();

    // 1. QUERY
    $iterator = $DB->request([
        'SELECT'     => [
            'glpi_tickets.id', 
            'glpi_tickets.name', 
            'glpi_tickets.date',
            'glpi_tickets.date_mod', 
            'glpi_tickets.status',
            'glpi_itilcategories.completename AS cat_name',
            'glpi_entities.completename AS entity_name'
        ],
        'FROM'       => 'glpi_tickets',
        'INNER JOIN' => [
            'glpi_tickets_users' => [
                'ON' => [
                    'glpi_tickets_users' => 'tickets_id',
                    'glpi_tickets'       => 'id'
                ]
            ]
        ],
        'LEFT JOIN' => [
            'glpi_itilcategories' => [
                'ON' => [
                    'glpi_tickets'        => 'itilcategories_id',
                    'glpi_itilcategories' => 'id'
                ]
            ],
            'glpi_entities' => [
                'ON' => [
                    'glpi_tickets'  => 'entities_id',
                    'glpi_entities' => 'id'
                ]
            ]
        ],
        'WHERE'      => [
            'glpi_tickets_users.users_id' => $myID,
            'glpi_tickets_users.type'     => 2,
            'glpi_tickets.status'         => ['NOT IN', [5, 6]],
            'glpi_tickets.is_deleted'     => 0
        ],
        'ORDER'      => "$db_order_field $sort_ord"
    ]);

    // 2. CONTADORES
    $tickets = iterator_to_array($iterator);
    $count_total = count($tickets);
    $count_novo = 0; $count_atendimento = 0; $count_pendente = 0;

    foreach ($tickets as $t) {
        $st = $t['status'];
        if ($st == 1) $count_novo++;
        if ($st == 2 || $st == 3) $count_atendimento++;
        if ($st == 4) $count_pendente++;
    }

    // 3. DASHBOARD
    echo "<div class='row'>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-red'><h2>{$count_novo}</h2><span><i class='fas fa-star'></i> {$LANG['card_new']}</span></div></div>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-green'><h2>{$count_atendimento}</h2><span><i class='fas fa-tools'></i> {$LANG['card_process']}</span></div></div>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-orange'><h2>{$count_pendente}</h2><span><i class='fas fa-pause-circle'></i> {$LANG['card_pending']}</span></div></div>";
    echo "<div class='col-md-3'><div class='card-stats bg-gradient-blue'><h2>{$count_total}</h2><span><i class='fas fa-list'></i> {$LANG['card_total']}</span></div></div>";
    echo "</div>";

    // 4. TABELA
    echo "<div class='card border-primary'>";
    echo "<div class='card-header' style='background-color: #343a40; color: white;'><h3 class='card-title'><i class='fas fa-user-md'></i> {$LANG['list_title']}</h3></div>";
    echo "<div class='card-body p-0'>";

    if ($count_total > 0) {
        echo "<table class='table table-striped table-hover m-0'>";
        
        echo "<thead class='thead-light'><tr>
                <th style='width: 5%'>" . getSortLink('id', $LANG['col_id'], $sort_col, $sort_ord) . "</th>
                <th style='width: 12%'>" . getSortLink('entity', $LANG['col_entity'], $sort_col, $sort_ord) . "</th>
                <th style='width: 10%'>" . getSortLink('status', $LANG['col_status'], $sort_col, $sort_ord) . "</th>
                <th>" . getSortLink('name', $LANG['col_title'], $sort_col, $sort_ord) . "</th>
                <th style='width: 8%' class='text-center'>{$LANG['col_approv']}</th>
                <th style='width: 14%'>{$LANG['col_req']}</th>
                <th style='width: 12%'>" . getSortLink('date', $LANG['col_date'], $sort_col, $sort_ord) . "</th>
                <th style='width: 12%'>" . getSortLink('date_mod', $LANG['col_update'], $sort_col, $sort_ord) . "</th>
                <th style='width: 8%' class='text-center'>{$LANG['col_action']}</th>
              </tr></thead>";
        echo "<tbody>";

        $status_map = [
            1 => $LANG['st_new'], 2 => $LANG['st_assign'], 3 => $LANG['st_plan'], 
            4 => $LANG['st_wait'], 5 => $LANG['st_solved'], 6 => $LANG['st_closed']
        ];

        foreach ($tickets as $data) {
            $st_text = $status_map[$data['status']] ?? $data['status'];
            $link = "../../../front/ticket.form.php?id=" . $data['id'];
            $categoria = !empty($data['cat_name']) ? $data['cat_name'] : '-';
            
            // Entidade
            $entity_text = !empty($data['entity_name']) ? $data['entity_name'] : '-';
            
            $ts_open = strtotime($data['date']);
            $date_open_br = "<strong>" . date("d/m/Y", $ts_open) . "</strong><br>" . date("H:i", $ts_open);

            // Approval
            $valid_iter = $DB->request(['FROM' => 'glpi_ticketvalidations', 'WHERE' => ['tickets_id' => $data['id']], 'ORDER' => 'id DESC', 'LIMIT' => 1]);
            $approval_badge = "<span class='text-muted'>-</span>";
            if (count($valid_iter) > 0) {
                $vrow = $valid_iter->current();
                if ($vrow['status'] == 2) $approval_badge = "<span class='badge-aprov-waiting'><i class='fas fa-clock'></i></span>";
                if ($vrow['status'] == 3) $approval_badge = "<span class='badge-aprov-ok'><i class='fas fa-check'></i></span>";
                if ($vrow['status'] == 4) $approval_badge = "<span class='badge-aprov-no'><i class='fas fa-times'></i></span>";
            }

            // Last Update
            $followup_iter = $DB->request(['FROM' => 'glpi_itilfollowups', 'WHERE' => ['items_id' => $data['id'], 'itemtype' => 'Ticket'], 'ORDER' => 'date_creation DESC', 'LIMIT' => 1]);
            $last_update_br = "<span class='text-muted small'>-</span>";
            if (count($followup_iter) > 0) {
                $frow = $followup_iter->current();
                $last_update_br = "<strong>" . date("d/m/Y", strtotime($frow['date_creation'])) . "</strong><br>" . date("H:i", strtotime($frow['date_creation']));
            } else {
                if ($data['date_mod'] > $data['date']) {
                     $last_update_br = "<strong>" . date("d/m/Y", strtotime($data['date_mod'])) . "</strong><br>" . date("H:i", strtotime($data['date_mod']));
                }
            }

            // Requester
            $req_iter = $DB->request(['SELECT' => ['glpi_users.firstname', 'glpi_users.realname', 'glpi_users.name'], 'FROM' => 'glpi_tickets_users', 'INNER JOIN' => ['glpi_users' => ['ON' => ['glpi_tickets_users' => 'users_id', 'glpi_users' => 'id']]], 'WHERE' => ['glpi_tickets_users.tickets_id' => $data['id'], 'glpi_tickets_users.type' => 1], 'LIMIT' => 1]);
            $r_name = "<i>{$LANG['no_req']}</i>";
            if (count($req_iter) > 0) {
                $r = $req_iter->current();
                $full = trim($r['firstname'] . ' ' . $r['realname']);
                $r_name = empty($full) ? $r['name'] : $full;
            }

            echo "<tr>";
            echo "<td class='font-weight-bold'>#{$data['id']}</td>";
            
            
            echo "<td style='font-size:0.85em; font-weight:500;' class='ticket-sub'>{$entity_text}</td>";
            
            echo "<td>{$st_text}</td>";
            
            
            echo "<td>
                    <div style='font-weight:bold;'><a href='$link' class='ticket-link'>{$data['name']}</a></div>
                    <div style='font-size:0.85em;' class='ticket-sub'><i class='fas fa-folder-open'></i> {$categoria}</div>
                  </td>";
            
            echo "<td class='text-center'>{$approval_badge}</td>";
            echo "<td><i class='fas fa-user text-muted mr-1'></i> {$r_name}</td>";
            echo "<td style='font-size:0.9em;'>{$date_open_br}</td>";
            echo "<td style='font-size:0.9em;'>{$last_update_br}</td>";
            echo "<td class='text-center'><a href='$link' class='btn btn-warning btn-sm btn-block text-dark' style='font-weight: bold;'><i class='fas fa-eye'></i> {$LANG['btn_check']}</a></td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<div class='p-5 text-center text-muted'><h4>{$LANG['empty_title']}</h4><p>{$LANG['empty_msg']}</p></div>";
    }

    echo "</div></div>"; 

} catch (\Throwable $e) {
    echo "<div class='alert alert-danger m-3'><h4>🚨 Error:</h4>" . $e->getMessage() . "</div>";
}

echo "</div>"; 
Html::footer();