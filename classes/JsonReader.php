<?php

class JsonReader {
    private $data;

    public function __construct($filePath) {
        $jsonData = file_get_contents($filePath);
        $this->data = json_decode($jsonData);
    }

    public function buildList() {
        $plans = $this->data->plans;
        $currentDate = date("Y-m-d h:i:s");

        $resources = array_filter($plans, function ($var) use ($currentDate) {
            return $var->schedule->startDate < $currentDate;
        });

        usort($resources, function($a, $b) {
            return $a->schedule->startDate < $b->schedule->startDate ? 1 : -1;
        });

        usort($resources, function($a, $b) {
            return $a->localidade->prioridade < $b->localidade->prioridade ? 1 : -1;
        });

        $filterLocality = $this->filterParams($resources, 'locality');
        $filterName = $this->filterParams($filterLocality, 'name');

        $this->data->plans = $filterName;

        return $this->data;
    }

    private function filterParams(array $data = [], string $type = 'name') {
        $list = [];

        foreach ($data as $item) {
            if ($type == 'locality') {
                $i = $item->localidade->nome;
            }

            if ($type == 'name') {
                $i = $item->name;
            }

            if (!isset($list[$i])) {
                $list[$i] = $item;
            }
        }
        return $list;
    }

    public function formatToMoney ($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
?>
