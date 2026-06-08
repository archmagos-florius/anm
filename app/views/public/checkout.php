<section class="page-hero compact-hero">
    <p class="eyebrow">Checkout</p>
    <h1>Finish your <?= e(site_name()) ?> order.</h1>
    <p>Choose pickup or delivery, then add the best contact details for your order.</p>
</section>

<?php foreach ($errors as $error): ?>
    <article class="flash flash-error"><?= e($error) ?></article>
<?php endforeach; ?>

<section class="checkout-layout">
    <form class="checkout-form" method="post">
        <?= csrf_field() ?>
        <?php $fulfillmentValue = posted('fulfillment_type', 'pickup'); ?>
        <fieldset class="checkout-section fulfillment-section">
            <legend><span>1</span> Pickup or delivery</legend>
            <div class="choice-cards">
                <label class="choice-card">
                    <input type="radio" name="fulfillment_type" value="pickup" <?= checked($fulfillmentValue === 'pickup') ?>>
                    <span>
                        <strong>Pickup</strong>
                        <small>Pick up your order during the available window.</small>
                    </span>
                </label>
                <label class="choice-card">
                    <input type="radio" name="fulfillment_type" value="delivery" <?= checked($fulfillmentValue === 'delivery') ?>>
                    <span>
                        <strong>Delivery</strong>
                        <small>Delivery fee is added when your order is delivered.</small>
                    </span>
                </label>
            </div>
        </fieldset>

        <section class="checkout-section">
            <h2><span>2</span> Contact details</h2>
            <label>Name
                <input name="customer_name" value="<?= e(posted('customer_name', $user['name'] ?? '')) ?>" required>
            </label>
            <label>Email
                <input type="email" name="customer_email" value="<?= e(posted('customer_email', $user['email'] ?? '')) ?>" required>
            </label>
            <label>Phone
                <input name="customer_phone" value="<?= e(posted('customer_phone', $user['phone'] ?? '')) ?>" required>
            </label>
        </section>

        <section class="checkout-section delivery-section">
            <h2><span>3</span> Delivery details</h2>
            <p class="muted">Only required when delivery is selected.</p>
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
            <small class="muted">Start typing your address to use autocomplete when Google Maps is configured.</small>

            <?php if ($user): ?>
                <label><input type="checkbox" name="save_address" value="1"> Save this delivery address</label>
                <label>Address label
                    <input name="address_label" value="<?= e(posted('address_label', 'Home')) ?>">
                </label>
            <?php endif; ?>
        </section>

        <section class="checkout-section">
            <h2><span>4</span> Notes</h2>
            <label>Notes
                <textarea name="customer_notes" rows="3" placeholder="Add any pickup, delivery, or food notes."><?= e(posted('customer_notes')) ?></textarea>
            </label>
        </section>

        <button type="submit" class="place-order-button">Place order</button>
    </form>

    <aside class="checkout-summary">
        <?php require dirname(__DIR__) . '/partials/order-summary.php'; ?>
    </aside>
</section>

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
