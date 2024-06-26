<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $sql = "INSERT INTO products (name, description, price) VALUES ('$name', '$description', '$price')";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        $sql = "UPDATE products SET name='$name', description='$description', price='$price' WHERE id=$id";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $sql = "DELETE FROM products WHERE id=$id";
        $conn->query($sql);
    }
}

$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <header class="bg-primary text-white text-center py-3 mb-4">
            <h1>Product Management System</h1>
            <a href="logout.php" class="btn btn-secondary">Logout</a>
        </header>

        <div class="card mb-4">
            <div class="card-header">
                <h2>Create Product</h2>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Product Description</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Product Description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Product Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Product Price" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="create">Create</button>
                </form>
            </div>
        </div>

        <h2>Products List</h2>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Actions">
                            <button type="button" class="btn btn-update" data-toggle="modal" data-target="#updateModal<?php echo $product['id']; ?>">Update</button>
                            <form method="POST" style="display:inline-block">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="btn btn-delete" name="delete">Delete</button>
                            </form>
                        </div>

                        <!-- Update Modal -->
                        <div class="modal fade" id="updateModal<?php echo $product['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel<?php echo $product['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="updateModalLabel<?php echo $product['id']; ?>">Update Product</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                            <div class="form-group">
                                                <label for="name">Product Name</label>
                                                <input type="text" class="form-control" name="name" value="<?php echo $product['name']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Product Description</label>
                                                <textarea class="form-control" name="description"><?php echo $product['description']; ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="price">Product Price</label>
                                                <input type="number" step="0.01" class="form-control" name="price" value="<?php echo $product['price']; ?>">
                                            </div>
                                            <button type="submit" class="btn btn-primary" name="update">Update</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
