<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DamageRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final readonly class StatisticsController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $now = Carbon::now();

        $data = [
            'total_summary' => $this->getTotalSummary(),
            'monthly_requests' => $this->getMonthlyRequests($now),
            'category_statistics' => $this->getCategoryStatistics($now),
            'priority_statistics' => $this->getPriorityStatistics($now),
            'current_statistics' => $this->getCurrentStatistics(),
        ];

        return $this->present($data);
    }

    /**
     * @return array
     */
    private function getTotalSummary(): array
    {
        return [
            'total_requests' => DamageRequest::count(),
            'total_categories' => Category::count(),
        ];
    }

    /**
     * @param Carbon $now
     * @return array
     */
    private function getMonthlyRequests(Carbon $now): array
    {
        return DamageRequest::select(
            DB::raw('DATE_TRUNC(\'month\', created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', $now->copy()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get()
            ->map(fn($item) => [
                'month' => $item->month,
                'count' => $item->count,
            ])
            ->toArray();
    }

    /**
     * @param Carbon $now
     * @return array
     */
    private function getCategoryStatistics(Carbon $now): array
    {
        $categories = Category::with(['damageRequests' => function ($query) use ($now) {
            $query->where('created_at', '>=', $now->copy()->subMonths(6));
        }])
            ->get();

        $monthlyStats = [];

        foreach ($categories as $category) {
            $requests = $category->damageRequests->groupBy(function ($request) {
                return Carbon::parse($request->created_at)->format('Y-m');
            });

            $monthlyStats[$category->name] = [];

            for ($i = 0; $i < 6; $i++) {
                $monthKey = $now->copy()->subMonths($i)->format('Y-m');
                $monthlyStats[$category->name][$monthKey] = $requests->get($monthKey, collect())->count();
            }
        }

        return $monthlyStats;
    }

    /**
     * @param Carbon $now
     * @return array
     */
    private function getPriorityStatistics(Carbon $now): array
    {
        $priorities = ['low', 'mid', 'high', 'critical'];
        $stats = [];

        foreach ($priorities as $priority) {
            $stats[$priority] = DamageRequest::select(
                DB::raw('DATE_TRUNC(\'month\', created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
                ->where('priority', $priority)
                ->where('created_at', '>=', $now->copy()->subMonths(6))
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->get()
                ->mapWithKeys(fn($item) => [
                    Carbon::parse($item->month)->format('Y-m') => $item->count
                ])
                ->toArray();
        }

        return $stats;
    }

    /**
     * @return array
     */
    private function getCurrentStatistics(): array
    {
        $totalDefects = DamageRequest::count();

        $categoryStats = Category::withCount('damageRequests')
            ->get()
            ->mapWithKeys(fn($category) => [
                $category->name => $category->damage_requests_count
            ])
            ->toArray();

        $priorityStats = DamageRequest::select('priority', DB::raw('COUNT(*) as count'))
            ->whereIn('priority', ['low', 'mid', 'high', 'critical'])
            ->groupBy('priority')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->priority => $item->count
            ])
            ->toArray();

        return [
            'total_defects' => $totalDefects,
            'by_category' => $categoryStats,
            'by_priority' => $priorityStats,
        ];
    }
}
