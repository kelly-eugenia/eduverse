<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$host = "feenix-mariadb.swin.edu.au";
$user = "s104475686"; 
$pwd = "291205"; 
$sql_db = "s104475686_db"; 

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if ($conn->connect_error) {
    http_response_code(500); // Server error
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Get raw POST data
$input = json_decode(file_get_contents("php://input"), true);
$username = isset($input["username"]) ? trim($input["username"]) : "";
$password = isset($input["password"]) ? trim($input["password"]) : "";

$response = [];
$role = null;

// Look up the user
$stmtL = $conn->prepare("SELECT * FROM learners WHERE username = ?");
$stmtL->bind_param("s", $username);
$stmtL->execute();
$resultL = $stmtL->get_result();

$stmtI = $conn->prepare("SELECT * FROM instructors WHERE username = ?");
$stmtI->bind_param("s", $username);
$stmtI->execute();
$resultI = $stmtI->get_result();

// Learner login
if ($resultL->num_rows === 1) {
    $user = $resultL->fetch_assoc();

    if ($password !== $user["password"]) {
        echo json_encode(["error" => "Invalid password"]);
        exit();
    }

    $userID = $user["learnerID"];
    $role = "learner";

    // Fetch enrolledCourses
    $enrolledCourses = [];
    $enrolledQuery = $conn->prepare("SELECT courseID, enrolledDate, completedDate, progress FROM enrolledCourses WHERE learnerID = ?");
    $enrolledQuery->bind_param("i", $userID);
    $enrolledQuery->execute();
    $enrolledResult = $enrolledQuery->get_result();
    while ($row = $enrolledResult->fetch_assoc()) {
        $enrolledCourses[] = $row;
    }

    // Fetch likedCourses
    $likedCourses = [];
    $likedQuery = $conn->prepare("SELECT courseID FROM likedCourses WHERE learnerID = ?");
    $likedQuery->bind_param("i", $userID);
    $likedQuery->execute();
    $likedResult = $likedQuery->get_result();
    while ($row = $likedResult->fetch_assoc()) {
        $likedCourses[] = $row["courseID"];
    }

    $response = [
        "success" => true,
        "role" => $role,
        "userID" => $user["learnerID"],
        "firstName" => $user["firstName"],
        "lastName" => $user["lastName"],
        "dob" => $user["dob"],
        "username" => $user["username"],
        "email" => $user["email"],
        "enrolledCourses" => $enrolledCourses,
        "likedCourses" => $likedCourses
    ];

} 
// Instructor login
else if ($resultI->num_rows === 1) {
    $user = $resultI->fetch_assoc();

    if ($password !== $user["password"]) {
        echo json_encode(["error" => "Invalid password"]);
        exit();
    }

    $userID = $user["instructorID"];
    $role = "instructor";

    // Fetch addedCourses
    $addedCourses = [];
    $addedQuery = $conn->prepare("SELECT courseID FROM addedCourses WHERE instructorID = ?");
    $addedQuery->bind_param("i", $userID);
    $addedQuery->execute();
    $addedResult = $addedQuery->get_result();
    while ($row = $addedResult->fetch_assoc()) {
        $addedCourses[] = $row["courseID"];
    }

    $response = [
        "success" => true,
        "role" => $role,
        "userID" => $user["instructorID"],
        "firstName" => $user["firstName"],
        "lastName" => $user["lastName"],
        "dob" => $user["dob"],
        "username" => $user["username"],
        "email" => $user["email"],
        "addedCourses" => $addedCourses,
    ];
} else {
    echo json_encode(["success" => false, "error" => "Invalid username"]);
    exit();
}

echo json_encode($response);

$conn->close();
?>