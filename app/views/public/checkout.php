<h1>Checkout</h1>

<?php foreach ($errors as $error): ?>
    <article class="flash flash-error"><?= e($error) ?></article>
<?php endforeach; ?>

<?php require dirname(__DIR__) . '/partials/order-summary.php'; ?>

<form method="post">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Fulfillment</legend>
        <label><input type="radio" name="fulfillment_type" value="pickup" checked> Pickup</label>
        <label><input type="radio" name="fulfillment_type" value="delivery"> Delivery</label>
    </fieldset>

    <label>Name
        <input name="customer_name" value="<?= e(posted('customer_name', $user['name'] ?? '')) ?>" required>
    </label>
    <label>Email
        <input type="email" name="customer_email" value="<?= e(posted('customer_email', $user['email'] ?? '')) ?>" required>
    </label>
    <label>Phone
        <input name="customer_phone" value="<?= e(posted('customer_phone', $user['phone'] ?? '')) ?>" required>
    </label>

    <?php if ($addresses): ?>
        <label>Saved address
            <select id="saved-address">
                <option value="">Choose saved address</option>
                <?php foreach ($addresses as $address): ?>
                    <option value="<?= e($address['address']) ?>"><?= e($address['label']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
    <?php endif; ?>

    <label>Delivery address
        <textarea id="delivery-address" name="delivery_address" rows="3"><?= e(posted('delivery_address')) ?></textarea>
    </label>

    <?php if ($user): ?>
        <label><input type="checkbox" name="save_address" value="1"> Save this delivery address</label>
        <label>Address label
            <input name="address_label" value="<?= e(posted('address_label', 'Home')) ?>">
        </label>
    <?php endif; ?>

    <label>Notes
        <textarea name="customer_notes" rows="3"><?= e(posted('customer_notes')) ?></textarea>
    </label>

    <button type="submit">Place order</button>
</form>

<?php if (google_maps_api_key() !== ''): ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= e(google_maps_api_key()) ?>&libraries=places&callback=initAddressAutocomplete" async defer></script>
    <script>
        function initAddressAutocomplete() {
            const input = document.getElementById('delivery-address');
            if (input && window.google) {
                new google.maps.places.Autocomplete(input);
            }
        }
    </script>
<?php endif; ?>
<script>
    const savedAddress = document.getElementById('saved-address');
    const deliveryAddress = document.getElementById('delivery-address');
    if (savedAddress && deliveryAddress) {
        savedAddress.addEventListener('change', () => deliveryAddress.value = savedAddress.value);
    }
</script>
