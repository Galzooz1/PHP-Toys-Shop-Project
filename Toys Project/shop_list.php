<?php
header('Content-Type: application/json');
include "./connect.php";

  // (int) -> דואג לאס קיו אל אינג'קשיין שזה קשור למספרים 
  $perPage = (isset($_GET['perPage'])) ? (int)$_GET['perPage'] : 25;
  $page = (isset($_GET['page'])) ? (int)$_GET['page'] * $perPage : 0;
  $min = (isset($_GET['min'])) ? (int)$_GET['min'] : 0;
  $max = (isset($_GET['max'])) ? (int)$_GET['max'] : 9999;

  // WHERE true -> כדי שנוכל להמשיך את השאילתא ולהוציא נניח מנימום מקסימום
  $query = "SELECT * FROM shop WHERE true ";
  // אפשרות להוציא מידע על מוצר ספציפי
  if(isset($_GET['prodId'])){

// (int) -> דואג לאס קיו אל אינג'קשיין שזה קשור למספרים 
    $prodId = (int)$_GET['prodId'];
  
    $query = "SELECT * FROM shop WHERE id = '{$prodId}' ";
  }

  if(isset($_GET['catName'])){
    //  שיטה למנוע SQL INJEXTION שאנחנו מקבלים סטרינג
// למשל מונע את האפשרות הבאה להוציא את כל המידע מהמסד
$catName = $conn->real_escape_string($_GET['catName']);
  // חובה להוסיף את הגרש בין המשתנה כדי שזה יעבוד
$query = "SELECT * FROM shop WHERE category = '{$catName}' ";
}

if(isset($_GET['search'])){
    $searchFor = $conn->real_escape_string($_GET['search']);
    // חשוב הסוגריים לפני הלייק ואחרי כדי שיהיו המשכיות לקווארי
    $query .= " AND (name like '%{$searchFor}%' OR category like '%{$searchFor}%')";
  }

   // מוסיף טווח מחירים ולפני הסורט כי זה מסיים את ה WHERE
   $query .= " AND price > {$min} AND price < {$max}";

   if(isset($_GET['sort'])){
   
     $sortBy = $conn->real_escape_string($_GET['sort']);
     $query .= " ORDER BY {$sortBy} DESC";
   }
   
  // מוסיף את האפשרות לעמודים
  $query .= " LIMIT {$page},{$perPage}";

  $res = $conn->query($query);

  $json_ar = [];
  while($row = mysqli_fetch_assoc($res)){
    array_push($json_ar,(object)$row);
  }

  echo json_encode($json_ar);