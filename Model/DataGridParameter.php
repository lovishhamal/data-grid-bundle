<?php

/**
 * @copyright EveryWorkflow. All rights reserved.
 */

declare(strict_types=1);

namespace EveryWorkflow\DataGridBundle\Model;

use EveryWorkflow\CoreBundle\Model\DataObjectInterface;
use Symfony\Component\HttpFoundation\Request;

class DataGridParameter implements DataGridParameterInterface
{
    protected array $filters = [];
    protected array $options = [];

    protected DataObjectInterface $dataObject;

    public function __construct(DataObjectInterface $dataObject, array $filters = [], array $options = [])
    {
        $this->dataObject = $dataObject;
        $this->filters = $filters;
        $this->options = $options;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function setFromRequest(Request $request): self
    {
        $perPage = $request->query->getInt('per-page', 20);
        $limit = 20;
        if ($perPage >= 1) {
            $limit = $perPage;
        }
        $page = $request->query->getInt('page', 1);
        $skip = 0;
        if ($page >= 1) {
            $skip = ($page - 1) * $perPage;
        }
        $this->setOptions([
            'skip' => $skip,
            'limit' => $limit,
            'sort_field' => $request->query->get('sort-field'),
            'sort_order' => $request->query->get('sort-order'),
        ]);

        $filter = $request->query->get('filter');
        if (is_string($filter)) {
            try {
                $filterData = $this->getFilters();
                $filterData += json_decode($filter, true, 512, JSON_THROW_ON_ERROR);
                $this->setFilters($filterData);
            } catch (\Exception $e) {
                // Ignore filter data if unable to decode
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $data = $this->dataObject->toArray();
        $data[self::KEY_FILTERS] = $this->getFilters();
        $data[self::KEY_OPTIONS] = $this->getOptions();

        return $data;
    }
}
