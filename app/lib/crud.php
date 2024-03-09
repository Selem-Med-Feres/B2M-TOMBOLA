<?php


function DB_create($tableName, $columns, $values)
{
    $conn = connect_DB();

    $placeholders = implode(',', array_fill(0, count($values), '?'));
    $columnString = implode(',', $columns);

    $sql = "INSERT INTO $tableName ($columnString) VALUES ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($values)), ...$values);
    $result = $stmt->execute();

    $stmt->close();
    mysqli_close($conn);

    return $result;
}



function DB_read($tableName, $conditions, $columns = ['*'])
{
    $conn = connect_DB();

    $columnString = implode(',', $columns);

    $conditionString = '';
    $conditionValues = [];
    foreach ($conditions as $column => $value) {
        $conditionString .= ($conditionString === '') ? '' : ' AND ';
        $conditionString .= "$column = ?";
        $conditionValues[] = $value;
    }

    $sql = "SELECT $columnString FROM $tableName";
    if (!empty($conditionString)) {
        $sql .= " WHERE $conditionString";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!empty($conditionValues)) {
        $types = str_repeat('s', count($conditionValues));
        mysqli_stmt_bind_param($stmt, $types, ...$conditionValues);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $records = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_close($conn);

    return $records;
}

function DB_update($tableName, $updateData, $conditions)
{
    $conn = connect_DB();

    $updateString = '';
    $updateValues = [];
    foreach ($updateData as $column => $value) {
        $updateString .= ($updateString === '') ? '' : ', ';
        $updateString .= "$column = ?";
        $updateValues[] = $value;
    }

    $conditionString = '';
    $conditionValues = [];
    $conditionSigns = ['=', '<', '>', '<=', '>=', '<>'];

    foreach ($conditions as $column => $condition) {
        $conditionSign = '=';
        foreach ($conditionSigns as $sign) {
            if (strpos($condition, $sign) === 0) {
                $conditionSign = $sign;
                $condition = substr($condition, strlen($sign));
                break;
            }
        }

        $conditionString .= ($conditionString === '') ? '' : ' AND ';
        $conditionString .= "$column $conditionSign ?";
        $conditionValues[] = $condition;
    }

    $sql = "UPDATE $tableName SET $updateString";
    if (!empty($conditionString)) {
        $sql .= " WHERE $conditionString";
    }

    $stmt = mysqli_prepare($conn, $sql);

    $types = '';
    if (!empty($updateValues)) {
        $types .= str_repeat('s', count($updateValues));
    }

    if (!empty($conditionValues)) {
        $types .= str_repeat('s', count($conditionValues));
    }

    if (!empty($updateValues)) {
        mysqli_stmt_bind_param($stmt, $types, ...$updateValues, ...$conditionValues);
    } else {
        mysqli_stmt_bind_param($stmt, $types, ...$conditionValues);
    }

    $result = mysqli_stmt_execute($stmt);

    echo $conn->error;

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $result;
}

function DB_delete($tableName, $conditions)
{
    $conn = connect_DB();

    $conditionString = '';
    $conditionValues = [];
    foreach ($conditions as $column => $value) {
        $conditionString .= ($conditionString === '') ? '' : ' AND ';
        $conditionString .= "$column = ?";
        $conditionValues[] = $value;
    }

    $sql = "DELETE FROM $tableName";
    if (!empty($conditionString)) {
        $sql .= " WHERE $conditionString";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!empty($conditionValues)) {
        $types = str_repeat('s', count($conditionValues));
        mysqli_stmt_bind_param($stmt, $types, ...$conditionValues);
    }

    $result = mysqli_stmt_execute($stmt);

    mysqli_close($conn);

    return $result;
}


function DB_count($tableName, $conditions, $distinctColumns = ['*'])
{
    $conn = connect_DB();

    $conditionString = '';
    $conditionValues = [];
    $conditionSigns = ['=', '<', '>', '<=', '>=', '<>'];

    foreach ($conditions as $column => $condition) {
        $conditionSign = '=';
        foreach ($conditionSigns as $sign) {
            if (strpos($condition, $sign) === 0) {
                $conditionSign = $sign;
                $condition = substr($condition, strlen($sign));
                break;
            }
        }

        $conditionString .= ($conditionString === '') ? '' : ' AND ';
        $conditionString .= "$column $conditionSign ?";
        $conditionValues[] = $condition;
    }

    $distinctString = '*';
    if ($distinctColumns !== ['*']) {
        $distinctString = 'DISTINCT ' . implode(', ', $distinctColumns);
    }

    $sql = "SELECT COUNT($distinctString) AS count FROM $tableName";
    if (!empty($conditionString)) {
        $sql .= " WHERE $conditionString";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if (!empty($conditionValues)) {
        $types = str_repeat('s', count($conditionValues));
        mysqli_stmt_bind_param($stmt, $types, ...$conditionValues);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $count = 0;
    if ($result !== false) {
        $row = mysqli_fetch_assoc($result);
        $count = $row['count'];
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $count;
}
