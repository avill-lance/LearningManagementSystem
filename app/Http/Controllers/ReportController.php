<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(in_array($request->user()->role, ['Admin', 'Staff', 'Registrar', 'Accounting'], true), 403);

        $filters = $request->only(['search', 'action', 'entity_type', 'date_from', 'date_to']);

        // Audit log table (filtered + paginated)
        $logs = $this->filteredLogs($request)
            ->leftJoin('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->select(
                'audit_logs.log_id',
                'audit_logs.user_id',
                'audit_logs.action',
                'audit_logs.entity_type',
                'audit_logs.entity_id',
                'audit_logs.timestamp',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.role'
            )
            ->orderByDesc('audit_logs.timestamp')
            ->orderByDesc('audit_logs.log_id')
            ->paginate(15)
            ->withQueryString();

        // Summary cards (follow the active filters)
        $totalLogs = $this->filteredLogs($request)->count();
        $todayLogs = $this->filteredLogs($request)->whereDate('audit_logs.timestamp', today())->count();
        $activeUsers = $this->filteredLogs($request)->whereNotNull('audit_logs.user_id')->distinct()->count('audit_logs.user_id');
        $entityTypeCount = $this->filteredLogs($request)->distinct()->count('audit_logs.entity_type');

        // Analysis tables
        $logsByAction = $this->filteredLogs($request)
            ->select('audit_logs.action', DB::raw('count(*) as total'), DB::raw('max(audit_logs.timestamp) as last_seen'))
            ->groupBy('audit_logs.action')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $logsByEntity = $this->filteredLogs($request)
            ->select('audit_logs.entity_type', DB::raw('count(*) as total'), DB::raw('count(distinct audit_logs.entity_id) as records'))
            ->groupBy('audit_logs.entity_type')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topUsers = $this->filteredLogs($request)
            ->join('users', 'audit_logs.user_id', '=', 'users.user_id')
            ->select(
                'users.user_id',
                'users.first_name',
                'users.last_name',
                'users.role',
                DB::raw('count(*) as total'),
                DB::raw('max(audit_logs.timestamp) as last_activity')
            )
            ->groupBy('users.user_id', 'users.first_name', 'users.last_name', 'users.role')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Filter dropdown options (unfiltered)
        $actionOptions = DB::table('audit_logs')->distinct()->orderBy('action')->pluck('action');
        $entityOptions = DB::table('audit_logs')->distinct()->orderBy('entity_type')->pluck('entity_type');

        return view('admin.reports.index', compact(
            'filters',
            'logs',
            'totalLogs',
            'todayLogs',
            'activeUsers',
            'entityTypeCount',
            'logsByAction',
            'logsByEntity',
            'topUsers',
            'actionOptions',
            'entityOptions'
        ));
    }

    private function filteredLogs(Request $request): Builder
    {
        $query = DB::table('audit_logs');

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('audit_logs.action', 'like', $search)
                  ->orWhere('audit_logs.entity_type', 'like', $search)
                  ->orWhereIn('audit_logs.user_id', function ($sub) use ($search) {
                      $sub->select('user_id')
                          ->from('users')
                          ->where('first_name', 'like', $search)
                          ->orWhere('last_name', 'like', $search)
                          ->orWhere('email', 'like', $search);
                  });
            });
        }

        if ($request->filled('action')) {
            $query->where('audit_logs.action', $request->action);
        }

        if ($request->filled('entity_type')) {
            $query->where('audit_logs.entity_type', $request->entity_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('audit_logs.timestamp', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('audit_logs.timestamp', '<=', $request->date_to);
        }

        return $query;
    }
}
