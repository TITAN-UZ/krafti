<?php

namespace App\Processors\Admin\Charts;

use App\GetProcessor;
use App\Model\Order;
use App\Model\User;
use Carbon\Carbon;

class Orders extends GetProcessor
{
    protected $class = Order::class;
    protected $scope = 'orders';

    public function get()
    {
        $type = $this->getProperty('type', 'day');

        $date = $this->getProperty('date');
        if ($date && is_array($date)) {
            $date_start = Carbon::createFromFormat('Y-m-d', $date[0]);
            $date_end = Carbon::createFromFormat('Y-m-d', $date[1]);
        } else {
            $date_start = Carbon::createFromFormat('Y-m-d', '2019-09-11');
            $date_end = Carbon::now();
        }

        $orders = $users = [];

        switch ($type) {
            case 'year':
                $select =
                $group = 'YEAR(created_at)';
                $format = 'Y';
                break;
            case 'month':
                $select = 'DATE_FORMAT(created_at, "%Y-%m")';
                $group = 'MONTH(created_at), YEAR(created_at)';
                $format = 'Y-m';
                break;
            default:
                $select = $group = 'DATE(created_at)';
                $format = 'Y-m-d';
        }
        $dates = [$date_start->format('Y-m-d 00:00:00'), $date_end->format('Y-m-d 23:59:59')];
        $c = Order::query()
            ->whereBetween('created_at', $dates)
            ->where('service', '!=', 'internal')
            ->groupByRaw($group)
            ->orderBy('created_at', 'asc')
            ->selectRaw($select . ' as date, SUM(cost) as value');
        foreach ($c->get() as $row) {
            $orders[$row->date] = $row->value;
        }

        $c = User::query()->whereBetween('created_at', $dates)
            ->groupByRaw($group)
            ->orderBy('created_at', 'asc')
            ->selectRaw($select . ' as date, COUNT(*) as value');
        foreach ($c->get() as $row) {
            $users[$row->date] = $row->value;
        }

        $rows = [];
        while ($date_start->format($format) <= $date_end->format($format)) {
            $date = $date_start->format($format);
            $rows[] = [
                'date' => $date,
                'orders' => isset($orders[$date])
                    ? $orders[$date]
                    : 0,
                'users' => isset($users[$date])
                    ? $users[$date]
                    : 0,
            ];
            if ($type == 'year') {
                $date_start->addYear();
            } elseif ($type == 'month') {
                $date_start->addMonth();
            } else {
                $date_start->addDay();
            }
        }

        return $this->success([
            'total' => count($rows),
            'rows' => $rows,
        ]);
    }
}