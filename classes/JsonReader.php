<?php

class JsonReader {
    private $data;

    public function __construct($filePath) {
        $jsonData = file_get_contents($filePath);
        $this->data = json_decode($jsonData);
    }

    /**
     * Retona JSON formatado de acordo com as  regras
     */
    public function buildList() {
        $plans = $this->data->plans;
        $currentDate = date("Y-m-d h:i:s");

        // Filtando somente datas válidas
        $resources = array_filter($plans, function ($var) use ($currentDate) {
            return $var->schedule->startDate < $currentDate;
        });

        // Ordenamdo pela data mais recente
        usort($resources, function($a, $b) {
            return $a->schedule->startDate < $b->schedule->startDate ? 1 : -1;
        });

        // Ordernando pela prioridade da localidade
        usort($resources, function($a, $b) {
            return $a->localidade->prioridade < $b->localidade->prioridade ? 1 : -1;
        });

        // Agrupando por localidade
        $filterLocality = $this->filterParams($resources, 'locality');

        // Agrupando por nome do plano
        $filterName = $this->filterParams($filterLocality, 'name');

        // Atualiazando array de planos com os dados formatados de acordo com as regras
        $this->data->plans = $filterName;

        return $this->data;
    }

    /**
     * Função responsável por agrupar o array de planos
     */
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

    /**
     * Helper de formatação para real (R$)
     */
    public function formatToMoney ($value) {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}
?>
