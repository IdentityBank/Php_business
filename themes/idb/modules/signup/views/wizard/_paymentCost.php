<div class="signup-text-color" style="text-align: left;">
    <small style="font-size: 50px;">
        <?php if ($paymentAttributes['currencyDisplayPosition'] == 0) : ?>
            <sup style="font-size: 30px;"><?= $paymentAttributes['currency'] ?></sup>
            <strong><?= $paymentAttributes['value'] ?></strong>
        <?php else : ?>
            <strong><?= $paymentAttributes['value'] ?></strong>
            <span style="font-size: 30px;"><?= $paymentAttributes['currency'] ?></span>
        <?php endif; ?>
        <sub style="font-size: 15px;">/&nbsp;<?= $paymentAttributes['recurringPeriod'] ?></sub>
    </small>
</div>
