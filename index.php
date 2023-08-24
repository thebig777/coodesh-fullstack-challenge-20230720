<?php
require_once './includes/header.php';
require_once './classes/JsonReader.php';

$jsonReader = new JsonReader('./uploads/plans/data.json');
$data = $jsonReader->buildList();
?>

<header>
  <span><?= $data->Aparelho->name; ?></span>
</header>

<div class="card">
  <?php foreach ($data->plans as $entry) { ?>
    <h4> <?= $entry->type; ?> </h4>
    <table>
      <tr>
        <td> Pós Pago - À Vista</td>
        <td><?= $jsonReader->formatToMoney($entry->phonePriceOnPlan); ?></td>
      </tr>
      <tr>
        <td> Mensalidade total do Plano</td>
        <td><?= $jsonReader->formatToMoney($entry->monthly_fee); ?></td>
      </tr>
      <tr>
        <td> Pré-pago* À Vista</td>
        <td><?= $jsonReader->formatToMoney($entry->phonePrice); ?></td>
      </tr>
    </table>
  <?php } ?>
</div>

<?php
require_once './includes/footer.php';
