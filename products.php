<?php include 'db_connect.php'; ?>

<?php if($_SESSION['login_type'] == 1): ?>
    <a href="#" class="btn btn-primary btn-sm mb-4" data-toggle="modal" data-target="#addProductModal">Add Product</a>
<?php endif; ?>


<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm" enctype="multipart/form-data"> <!-- enctype attribute for handling file uploads -->
                    <div class="form-group">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="productName" required>
                    </div>
                    <div class="form-group">
                        <label for="productInfo">Product Info</label>
                        <textarea class="form-control" id="productInfo" name="productInfo" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="productQuantity">Quantity</label>
                        <input type="number" class="form-control" id="productQuantity" name="productQuantity" required>
                    </div>
                    <div class="form-group">
                        <label for="productPrice">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="productPrice" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="productExpirationDate">Expiration Date</label>
                        <input type="date" class="form-control" id="productExpirationDate" name="productExpirationDate" required>
                    </div>
                    <div class="form-group">
                        <label for="productImage">Product Image</label>
                        <input type="file" class="form-control-file" id="productImage" name="productImage" accept="image/*" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addProductBtn">Add Product</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php
    $qry = $conn->query("SELECT * FROM product");
    while ($row = $qry->fetch_assoc()) :
    ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card mb-4">
                <?php
                $image_data = $row['image'];
                $image_type = 'image/jpeg';

                echo '<img src="data:' . $image_type . ';base64,' . base64_encode($image_data) . '" class="card-img-top" alt="Product Image" style="height: 200px; width: 100%; object-fit: cover;">';
                ?>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $row['name'] ?></h5>
                    <p class="card-text"><?php echo $row['info'] ?></p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Quantity: <?php echo $row['quantity'] ?></li>
                    <li class="list-group-item">Price: $<?php echo $row['price'] ?></li>
                    <li class="list-group-item">Expiration Date: <?php echo date("M d, Y", strtotime($row['expiration_date'])) ?></li>
                </ul>
                <div class="card-body">
                     <?php if($_SESSION['login_type'] == 3): ?>
                    <button class="btn btn-primary btn-sm buy-now-btn" data-id="<?php echo $row['product_id'] ?>" data-toggle="modal" data-target="#productModal" 
data-name="<?php echo $row['name'] ?>"
data-info="<?php echo $row['info'] ?>"
data-quantity="<?php echo $row['quantity'] ?>"
data-price="<?php echo $row['price'] ?>"
data-expiration-date="<?php echo date("M d, Y", strtotime($row['expiration_date'])) ?>">Buy Now</button>


                    <a href="#" class="btn btn-success btn-sm">Add to Cart</a>
                <?php endif; ?>
                 <?php if($_SESSION['login_type'] == 1): ?>
                   <!--  <button class="btn btn-primary btn-sm buy-now-btn" data-id="<?php echo $row['product_id'] ?>" data-toggle="modal" data-target="#productModal" 
data-name="<?php echo $row['name'] ?>"
data-info="<?php echo $row['info'] ?>"
data-quantity="<?php echo $row['quantity'] ?>"
data-price="<?php echo $row['price'] ?>"
data-expiration-date="<?php echo date("M d, Y", strtotime($row['expiration_date'])) ?>">Edit</button> -->


                   <a href="#" class="btn btn-danger btn-sm delete-product-btn" data-id="<?php echo $row['product_id'] ?>">Delete</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<!-- Product Modal -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                 <input type="hidden" id="productIdInput">
                <h5 id="productName"></h5>
                <p id="productInfo"></p>
                <ul>
                    <li>Quantity: <span id="productQuantity"></span></li>
                    <li>Price: $<span id="productPrice"></span></li>
                    <li>Expiration Date: <span id="productExpirationDate"></span></li>
                </ul>
                <div class="form-group">
                    <label for="quantitySelect">Select Quantity:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" id="minusBtn">-</button>
                        </div>
                        <input type="text" class="form-control" id="quantitySelect" value="1">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="plusBtn">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="buyBtn">Buy</button>
            </div>
        </div>
    </div>
</div>


<script>
   // JavaScript to populate modal with product details when Buy Now button is clicked
$(document).ready(function() {
    $('.buy-now-btn').click(function() {
        var productId = $(this).data('id'); // Corrected line
        var productName = $(this).data('name');
        var productInfo = $(this).data('info');
        var productQuantity = $(this).data('quantity');
        var productPrice = $(this).data('price');
        var productExpirationDate = $(this).data('expiration-date');

        $('#productIdInput').val(productId);
        $('#productName').text(productName);
        $('#productInfo').text(productInfo);
        $('#productQuantity').text(productQuantity);
        $('#productPrice').text(productPrice);
        $('#productExpirationDate').text(productExpirationDate);
    });
});

</script>
<script>
    $(document).ready(function() {
        var quantity = 1; // Default quantity
        var maxQuantity = parseInt($('#productQuantity').text()); // Maximum available quantity

        // Function to update the quantity input value and prevent exceeding the maximum quantity
        function updateQuantity(newQuantity) {
            if (newQuantity < 1) {
                quantity = 1;
            } else if (newQuantity > maxQuantity) {
                quantity = maxQuantity;
            } else {
                quantity = newQuantity;
            }
            $('#quantitySelect').val(quantity);
        }

        // Plus button click event
        $('#plusBtn').click(function() {
            updateQuantity(quantity + 1);
        });

        // Minus button click event
        $('#minusBtn').click(function() {
            updateQuantity(quantity - 1);
        });
    });
</script>
<script>
    // JavaScript to handle Buy button click
    $(document).ready(function() {
       $('#buyBtn').click(function() {
    var productId = $('#productIdInput').val(); // Retrieve productId from hidden input field
    var productName = $('#productName').text();
    var productPrice = parseFloat($('#productPrice').text().replace('$', ''));
    var productQuantity = parseInt($('#quantitySelect').val());
    var totalCost = productPrice * productQuantity;

    // Redirect to place_order.php with parameters in URL
    window.location.href = 'place_order.php?productName=' + encodeURIComponent(productName) + '&productPrice=' + encodeURIComponent(productPrice) + '&productQuantity=' + encodeURIComponent(productQuantity) + '&totalCost=' + encodeURIComponent(totalCost) + '&productId=' + encodeURIComponent(productId);


        });
    });
</script>

<script>
    // JavaScript to handle showing the Add Product modal
    $(document).ready(function() {
        $('#addProductModal').on('shown.bs.modal', function () {
            $('#productName').focus(); // Focus on the first input field
        });
    });
</script>
<script>
    // JavaScript to handle adding a new product
    $(document).ready(function() {
        $('#addProductBtn').click(function() {
            // Serialize form data
            var formData = $('#addProductForm').serialize();

            // Send AJAX request to add_product.php
            $.post('add_product.php', formData, function(response) {
                if (response === 'success') {
                    // Reload the page after adding the product
                    location.reload();
                } else {
                    // Display error message or handle error case
                    console.log('Error adding product');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Event listener for Delete button click
        $('.delete-product-btn').click(function() {
            var productId = $(this).data('id');

            // Confirm before deleting
            if (confirm("Are you sure you want to delete this product?")) {
                // Send AJAX request to delete_product.php
                $.post('delete_product.php', { productId: productId }, function(response) {
                    if (response === 'success') {
                        // Reload the page after successful deletion
                        location.reload();
                    } else {
                        // Display error message or handle error case
                        console.log('Error deleting product');
                    }
                });
            }
        });
    });
</script>

