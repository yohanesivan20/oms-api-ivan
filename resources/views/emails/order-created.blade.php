<!DOCTYPE html>
<html>

    <body>
        <h2>Order Created</h2>

        <p>Hello {{ $order->customer_name }}</p>

        <p>Your order has been created successfully.</p>

        <p>
            Order Number :
            <strong>
            {{ $order->order_number }}
            </strong>
        </p>

        <p>
            Grand Total :
            <strong>
            {{ $order->grand_total }}
            </strong>
        </p>

    </body>

</html>