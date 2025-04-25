<?php

function LogAuditRecord(string $category, string $code, $level, string $description): void
{
    $username = $_SERVER['PHP_AUTH_USER'];
    try {
        $db = new PDO('mysql:host=iam-example-db-server;dbname=IAM;', "student", "test1234");

        $SQL = "INSERT INTO audittrail (category, code, level, username, description, timestamp) 
            VALUE(:category, :code, :level, :username, :description, now());";
        $stmt = $db->prepare($SQL);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':level', $level);

        $stmt->execute();
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
}
