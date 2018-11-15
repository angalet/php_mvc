<?php
if (isset($_POST['addtask']) and $_POST['task']){
    $data = [
        'user_id' => $_SESSION['user_id'],
        'description' => $_POST["task"],
        'assigned_user_id' => $_SESSION['user_id']
    ];
    $sql = "INSERT INTO task (user_id, description, assigned_user_id) VALUES (:user_id, :description, :assigned_user_id)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);
}

$data = [
    'user_id' => $_SESSION['user_id']
];
$sql = "SELECT id, description, date_added, is_done, assigned_user_id FROM task WHERE user_id=:user_id ORDER BY date_added ";
$stmt= $pdo->prepare($sql);
$stmt->execute($data); 
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
$users_from_base = "SELECT login, id FROM user ";
$stmt= $pdo->prepare($users_from_base);
$stmt->execute();
$assignedUserList = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($tasks as $row){
    if ($row['is_done'] == 0) {
        $task_state = "не выполнено";
        $task_state_make = 1;
    }
    else {
        $task_state = "выполнено";
        $task_state_make = 0;
}

        echo '<td><input type="checkbox" name="delete_row[]" value='.$row['id'].' /></td>';
        echo '<td>'.$row['id']." ".$row['description'].'</td>';
        echo '<td>'.date("Y M d",strtotime($row['date_added'])).'</td>';
        echo "<td><a href='/NET/mysql/3/?id=".$row['id']."&done=$task_state_make'>$task_state</a></td>";
        echo '<td>';
        echo "<select name='assigned_user_id[".$row['id']."]' >";
        foreach ($assignedUserList as $assignedUser){
            echo '<option ';
            if ($row['assigned_user_id'] == $assignedUser['id']){
                echo 'selected'; } 
                    echo ' value='.$assignedUser['id'].' >'.$assignedUser['login'].'</option>';
          }
        echo '</select></td></tr>';
}

$data = [
    'user_id' => $_SESSION['user_id']
];
$sql = "SELECT t.id, t.description, date_added, is_done, assigned_user_id, t.user_id, u.login as name1, ut.login  FROM task t 
JOIN user u ON u.id=t.assigned_user_id 
JOIN user ut ON ut.id=t.user_id
WHERE t.user_id <>:user_id and t.assigned_user_id = :user_id";
$stmt= $pdo->prepare($sql);
$stmt->execute($data); 
$assigned_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($assigned_tasks as $assigned_task){
    if ($assigned_task['is_done'] == 0) {
        $task_state = "не выполнено";
        $task_state_make = 1;
    }
    else {
        $task_state = "выполнено";
        $task_state_make = 0;
    }

    echo "<tr><td><input type='checkbox' disabled   /></td>";
    echo "<td>".$assigned_task['id']." ".$assigned_task['description']."</td>";
    echo "<td>".date("Y M d",strtotime($assigned_task['date_added']))."</td>";
    echo "<td><a href='/NET/mysql/3/?id=".$assigned_task['id']."&done=".$task_state_make."&assigned_=1'>".$task_state."</a></td>";
    echo "<td>".$assigned_task['login']."->".$assigned_task['name1']."</td></tr>";
}
$data = [
    'user_id' => $_SESSION['user_id']
];
$user_count_from_base = "SELECT count(*) FROM task t WHERE t.user_id =:user_id  OR t.assigned_user_id = :user_id";
$stmt= $pdo->prepare($user_count_from_base);
$stmt->execute($data);
$user_count  = $stmt->fetch();
    echo "<tr><td></td><td>Количество задач</td>";
    echo "<td>".$user_count[0]."</td></tr></table>";
    echo "<p><input type='submit' name='delete_row_submit' value='OK' > удалить выбранное</p>";
    echo "<p><input type='submit' name='deleg_to_user' value='OK' > делегировать выбранное</p></form>";
if (isset($_POST['delete_row_submit']) and isset($_POST['delete_row']) ){
    print_r($_POST['delete_row']);
    $id_task = NULL;
    $id_task = implode(',',$_POST['delete_row']);
    $data = [
        'user_id' => $_SESSION['user_id']
    ];
    $sql = "DELETE FROM task WHERE user_id=:user_id AND id IN (".$id_task.")";
    //DELETE FROM task WHERE user_id= ... AND id=... LIMIT 1 не стал так телать, хотелось удалять сразу
    //несколько задач
    //$sql = "DELETE FROM task WHERE id IN (:$id_task)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data); //тут почему то не работает через $data это  выражение id IN (:$id_task)
    header("Location: http://".$_SERVER['HTTP_HOST']."/NET/mysql/3/");
}
if (isset($_POST['deleg_to_user'])){  
    $assigned_users_id_from_form =$_POST['assigned_user_id'];
    foreach ($assigned_users_id_from_form as $key => $assigned_user_id_from_form){
        $data = [
            'user_id' => $_SESSION['user_id'],
            'id' => $key,
            'assigned_user_id' => $assigned_user_id_from_form
        ];
        $sql = "UPDATE task SET assigned_user_id=:assigned_user_id WHERE id=:id AND user_id=:user_id";
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);
        
    }
    header("Location: http://".$_SERVER['HTTP_HOST']."/NET/mysql/3/"); //пытался так обновлять страницы, но нотайсы
}
if (isset($_GET['done'])){
    $user_id_or_assig = 'user_id';
    if (isset($_GET['assigned_'])){
    if ($_GET['assigned_']==1) $user_id_or_assig = 'assigned_user_id';
    else $user_id_or_assig = 'user_id';
    }
    $data = [
        'id' => $_GET['id'],
        'is_done' => $_GET['done'],
        $user_id_or_assig => $_SESSION['user_id']
    ];
    $sql = "UPDATE task SET is_done=:is_done WHERE ".$user_id_or_assig."=:".$user_id_or_assig." AND id=:id LIMIT 1";
    $stmt= $pdo->prepare($sql);
    $stmt->execute($data);
    header("Location: http://".$_SERVER['HTTP_HOST']."/NET/mysql/3/");
}
if (isset($_POST['logout'])){ 
    $_SESSION['user_id'] = NULL;  
    $_SESSION['NAME'] = NULL;
    header("Location: http://".$_SERVER['HTTP_HOST']."/NET/mysql/3/");
}