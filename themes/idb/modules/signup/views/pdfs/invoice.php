<!DOCTYPE html>
<html lang="en-GB" dir="ltr">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"/>
</head>
<body style="font-family: 'Ubuntu', sans-serif; font-size: 100%; margin: 3%;">
<header>
    <div class="header-image" style="
    width: 100%;
    height: 106px;
    background-color: #00AEAB;
    background-image: url(https://www.identitybank.eu/images/homeafbeeldingen/logo2new.png);
    background-repeat: no-repeat;
">&nbsp;
    </div>
    <hr style="color: #00AEAB; opacity: 50%;">
    <section class="header-container" style="display: flex; padding: 10px 10px 0px 10px;">
        <div class="header-address" style="float:left; width: 50%; text-align: left;">
            <p><strong>Correspondence Address:</strong></p>
            <p><strong>Identity Bank BV</strong></p>
            <p>Postbus 6676 | 6503GD Nijmegen | Nederland</p>
        </div>
        <div class="header-web" style="float:right; width: 50%; text-align: right;">
            <p><strong>Web:</strong>&nbsp;www.identitybank.eu</p>
            <p><strong>Customer service:</strong>&nbsp;billing@identitybank.eu</p>
        </div>
    </section>
    <hr style="color: #00AEAB; opacity: 50%;">
    <div class="header-summary" style="
        background-color: #00AEAB;
        color: white;
        font-size: large;
        display: flex;
        text-transform: uppercase;
        padding: 10px 10px 10px 10px;">
        <div class="header-summary-left" style="
            float:left;
            width: 50%;
            display:inline-block;
            text-align: left;">
            Invoice Number: <?= $invoice->invoice_number ?>;
        </div>
        <div class="header-summary-right" style="
            float:right;
            width: 50%;
            text-align: right;">
            Invoice Date: <?= (new DateTime($invoice->timestamp))->format('Y M d') ?>
        </div>
    </div>
    <div class="header-service-period" style="
        text-transform: uppercase;
        padding: 30px 10px 30px 10px;">
        This invoice details service charges for the period: &lt;3 April 2019 - 2 May 2019&gt;
    </div>
</header>
<section class="recipient">
    <div class="recipient-title" style="
        text-transform: uppercase;
        padding-left: 10px; margin-bottom: -10px; padding-bottom: 0;">
        Bill to
    </div>
    <hr style="color: #00AEAB; opacity: 50%;">
    <div class="recipient-details" style="padding-left: 10px;">
        <p>&lt;Business Contact Name&gt;<br/>
            &lt;Business Name&gt;<br/>
            &lt;Address 1&gt; | &lt;Address 2&gt; | &lt;City&gt; | &lt;Postcode&gt; | &lt;Country&gt;<br/>
            &lt;VAT Number&gt;</p>
    </div>
</section>
<section class="items-table">
    <table width=100%>
        <tr class="items-header" style="
        background-color: #00AEAB;
        color: white;
        font-size: large;
        text-transform: uppercase;
        font-family: 'Ubuntu', sans-serif;">
            <th class="items-th-quantity" style="
            font-weight: normal;
            text-align: left;
            color:white;
            padding: 5px 10px 5px 10px;">
                Quantity
            </th>
            <th class="items-th-description" style="
            font-weight: normal;
        text-align: left;
                    color:white;
        padding: 5px 10px 5px 10px;">
                Description
            </th>
            <th class="items-th-unitprice" style="
            font-weight: normal;
        text-align: right;
                    color:white;
        padding: 5px 10px 5px 10px;">
                Unit Price
            </th>
            <th class="items-th-total" style="
            font-weight: normal;
            text-align: right;
                        color:white;
            padding: 5px 10px 5px 10px;">
                Total
            </th>
        </tr>
        <tr class="items-row">
            <td style="
            border:0;
            font-weight: normal;
        text-align: left;
        padding: 5px 10px 5px 10px;" class="item-quantity">1
            </td>
            <td style="
                border:0;
                font-weight: normal;
                text-align: left;
                padding: 5px 10px 5px 10px;" class="item-description">
                Starter Plan Monthly Subscription
            </td>
            <td style="
            border:0;
            font-weight: normal;
            text-align: right;
            padding: 5px 10px 5px 10px;" class="item-unitprice">&euro; 50
            </td>
            <td style="
                border:0;
                font-weight: normal;
                text-align: right;
                padding: 5px 10px 5px 10px;" class="item-total">
                &euro; 50
            </td>
        </tr>
        <tr class="item-hr">
            <td style="
                border:0;
                padding: 0;
                margin: 0;" colspan=4>
                <hr>
            </td>
        </tr>
        <tr class="items-subtotal">
            <td style="
            border:0;
       text-align: right;
        text-transform: uppercase;
        font-weigth: bold;
        padding: 5px 10px 5px 10px;" colspan=3 class="items-subtotal-title">Subtotal
            </td>
            <td style="
            border:0;
            font-weight: normal;
            text-align: right;
            padding: 5px 10px 5px 10px;" class="items-subtotal-total">&euro; 1500
            </td>
        </tr>
        <tr class="item-hr">
            <td style="border:0;" colspan=4>
                <hr>
            </td>
        </tr>
        <tr class="items-discount">
            <td style="
            border:0;
       text-align: right;
        text-transform: uppercase;
        font-weigth: bold;
        padding: 5px 10px 5px 10px;" colspan=3 class="items-discount-title">Discount 30%
            </td>
            <td style="
            border:0;
            font-weight: normal;
            text-align: right;
            padding: 5px 10px 5px 10px;" class="items-discount-total">&euro; 450
            </td>
        </tr>
        <tr class="item-hr">
            <td style="border:0;" colspan=4>
                <hr>
            </td>
        </tr>
        <tr class="items-tax">
            <td style="
            border:0;
       text-align: right;
        text-transform: uppercase;
        font-weigth: bold;
        padding: 5px 10px 5px 10px;" colspan=3 class="items-tax-title">BTW 21%
            </td>
            <td style="
            border:0;
            font-weight: normal;
            text-align: right;
            padding: 5px 10px 5px 10px;
            " class="items-tax-total">&euro; 300
            </td>
        </tr>
        <tr class="items-total" style="
        background-color: #00AEAB;
        color: white;
        font-size: large;
        text-transform: uppercase;
        font-family: 'Ubuntu', sans-serif;">
            <td style="border:0;" colspan=2 class="items-total-thanks" style="
            font-weight: normal;
            text-align: left;
            color: white;
            padding: 5px 10px 5px 10px;">
                Thank you for your payment
            </td>
            <td style="border:0;" class="items-total-title" style="
                font-weight: normal;
                text-align: right;
                color:white;
                padding: 5px 10px 5px 10px;">
                Total Payment
            </td>
            <td style="
                border:0;
                font-weight: normal;
                text-align: right;
                color: white;
                padding: 5px 10px 5px 10px;" class="items-total-total">
                &euro; 1300
            </td>
        </tr>
    </table>
    <div style="text-align: center;" class="disclaimer">
        <p>Your payment has been made in accordance with IDB Terms and Conditions for our IDB Service Plans.<br/>
            The IDB Terms and Conditions can be accessed and viewed from your IDB business account.</p>
    </div>
</section>
<hr style="color: #00AEAB; opacity: 50%;">
<footer style="padding-top: 0px;">
    <div style="
        width: 100%;
        text-align: center;
        text-transform: uppercase;
        padding: 0 10px 5px 10px;" class="idb-details-large">
        KVK: 72407891 | BTW: NL859100273B01 | IBAN: NL20RABO0332470105
    </div>
    <div style="
    font-size: x-small;
        width: 100%;
        text-align: center;
        text-transform: uppercase;
        padding: 0 10px 5px 10px;
        " class="idb-details-small">
        Identity Bank BV | Registered Address: Stiftstraat 16, 6584AL Molenhoek, Nederland
    </div>
</footer>
</body>
</html>
