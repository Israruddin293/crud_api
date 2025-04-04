<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Handle logout request
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit;
}

// Restrict actions based on role
$role = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : null;

if (!$role) {
    // Handle the case where the role is not set
    echo "Error: User role is not defined.";
    exit;
}

// Add token to JavaScript
$token = isset($_SESSION['user']['token']) ? $_SESSION['user']['token'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Web App</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        const token = "<?php echo $token; ?>";

        async function fetchUsers() {
            try {
                const response = await fetch("api/users.php", {
                    headers: { "Authorization": `Bearer ${token}` }
                });
                if (!response.ok) throw new Error("Failed to fetch users");
                const responseText = await response.text();
                let users;
                try {
                    users = JSON.parse(responseText);
                } catch (e) {
                    console.error("Invalid JSON response:", responseText);
                    throw new Error(`Invalid JSON response from server: ${responseText}`);
                }
                const tableBody = document.querySelector(".user-table tbody");
                tableBody.innerHTML = "";
                users.forEach(user => {
                    const row = `<tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="edit-btn" onclick="updateUser(${user.id})">Edit</button>
                            <button class="delete-btn" onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } catch (error) {
                console.error("Error fetching users:", error);
                alert(error.message);
            }
        }

        function addUser() {
            let name = document.getElementById("name").value;
            let email = document.getElementById("email").value;

            fetch("api/users.php", {
                method: "POST",
                headers: { 
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`
                },
                body: JSON.stringify({ name, email })
            })
            .then(async response => {
                if (!response.ok) throw new Error("Failed to add user");
                const responseText = await response.text();
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error("Invalid JSON response:", responseText);
                    throw new Error(`Invalid JSON response from server: ${responseText}`);
                }
                alert(data.message);
                fetchUsers();
            })
            .catch(error => {
                console.error("Error adding user:", error);
                alert(error.message);
            });
        }

        function deleteUser(id) {
            fetch(`api/users.php?id=${id}`, {
                method: "DELETE",
                headers: { "Authorization": `Bearer ${token}` }
            })
            .then(async response => {
                if (!response.ok) throw new Error("Failed to delete user");
                const responseText = await response.text();
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error("Invalid JSON response:", responseText);
                    throw new Error(`Invalid JSON response from server: ${responseText}`);
                }
                alert(data.message);
                fetchUsers();
            })
            .catch(error => {
                console.error("Error deleting user:", error);
                alert(error.message);
            });
        }

        function updateUser(id) {
            let newName = prompt("Enter new name:");
            let newEmail = prompt("Enter new email:");
            
            if (newName && newEmail) {
                fetch("api/users.php", {
                    method: "PUT",
                    headers: { 
                        "Content-Type": "application/json",
                        "Authorization": `Bearer ${token}`
                    },
                    body: JSON.stringify({ id, name: newName, email: newEmail })
                })
                .then(async response => {
                    if (!response.ok) throw new Error("Failed to update user");
                    const responseText = await response.text();
                    let data;
                    try {
                        data = JSON.parse(responseText);
                    } catch (e) {
                        console.error("Invalid JSON response:", responseText);
                        throw new Error(`Invalid JSON response from server: ${responseText}`);
                    }
                    alert(data.message);
                    fetchUsers();
                })
                .catch(error => {
                    console.error("Error updating user:", error);
                    alert(error.message);
                });
            }
        }

        document.addEventListener("DOMContentLoaded", fetchUsers);
    </script>
</head>
<body>
    <div class="container">
        <h2>Users List</h2>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Users will be dynamically loaded here -->
            </tbody>
        </table>

        <?php if ($role === 'admin'): ?>
        <h2>Add User</h2>
        <div class="form-container">
            <input type="text" id="name" placeholder="Enter Name" required>
            <input type="email" id="email" placeholder="Enter Email" required>
            <button class="add-btn" onclick="addUser()">Add User</button>
        </div>
        <?php endif; ?>
        <a href="index.php?action=logout" class="logout-btn">Logout</a>
    </div>
</body>
</html>
