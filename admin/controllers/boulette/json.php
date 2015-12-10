<?php   

    $start = 0;
    $qsStart = (int)$_GET["start"];
    $search = $_GET["search"];
    $order = $_GET["order"];
    $columns = $_GET["columns"];
    $qsLength = (int)$_GET["length"];    
    
    if($qsStart) {
        $start = $qsStart;
    }    
    
    $index = $start;   
    $rowsPerPage = $qsLength;
       
    $rows = array();
    
    $searchValue = $search['value'];
    $orderValue = $order[0];
    
    $orderClause = "";
    if($orderValue) {
        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
    }
    
    $table_columns = array(
        'id_boulette', 
        'timestamp', 

    );
    
    $table_columns_type = array(
        'INTEGER', 
        'DATETIME', 
    );    
    
    $whereClause = "";
    
    $i = 0;
    foreach($table_columns as $col){
        
        if ($i == 0) {
           $whereClause = " WHERE";
        }
        
        if ($i > 0) {
            $whereClause =  $whereClause . " OR"; 
        }
        
        $whereClause =  $whereClause . " " . $col . " LIKE '%". $searchValue ."%'";
        
        $i = $i + 1;
    }
    
    $rowTotal = $file_db->prepare("SELECT COUNT(*) as count FROM `boulette`" . $whereClause . $orderClause);
    $rowTotal->execute();
    $order_items = $rowTotal->fetchAll();
    $recordsTotal = $order_items[0]['count'];

    $findSQL = $file_db->prepare("SELECT datetime(timestamp, 'unixepoch', 'localtime') as timestamp, boulette.id_boulette FROM `boulette`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage);
    $findSQL->execute();
    $rows_sql = $findSQL->fetchAll();

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

        if( $table_columns_type[$i] != "blob") {
                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
        } else {                if( !$row_sql[$table_columns[$i]] ) {
                        $rows[$row_key][$table_columns[$i]] = "0 Kb.";
                } else {
                        $rows[$row_key][$table_columns[$i]] = " <a target='__blank' href='menu/download?id=" . $row_sql[$table_columns[0]];
                        $rows[$row_key][$table_columns[$i]] .= "&fldname=" . $table_columns[$i];
                        $rows[$row_key][$table_columns[$i]] .= "&idfld=" . $table_columns[0];
                        $rows[$row_key][$table_columns[$i]] .= "'>";
                        $rows[$row_key][$table_columns[$i]] .= number_format(strlen($row_sql[$table_columns[$i]]) / 1024, 2) . " Kb.";
                        $rows[$row_key][$table_columns[$i]] .= "</a>";
                }
        }

        }
    }    
    
    $queryData = array();
    $queryData['start']= $start;
    $queryData['recordsTotal'] = $recordsTotal;
    $queryData['recordsFiltered'] = $recordsTotal;
    $queryData['data'] = $rows;
    
    echo json_encode($queryData);