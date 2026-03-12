<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Web\Admin;

use App\Application\Contracts\QueryBusInterface;
use App\Application\Features\Children\Queries\GetAllChildren\GetAllChildrenQuery;
use App\Presentation\ViewModels\Web\MasterData\ChildListViewModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

final readonly class ChildController
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {}

    public function index(Request $request): View
    {
        $page    = (int) $request->query('page', 1);
        $perPage = 10;

        $result = $this->queryBus->dispatch(new GetAllChildrenQuery(page: $page, perPage: $perPage));
        // We need to adjust ChildListViewModel because earlier it was taking an array of ChildDTOs, but now it's PaginatedResult
        // Wait, the viewModel currently accepts `array $children`. Let's pass the array of items and wrap the paginator manually, or we update the ViewModel.
        // Actually, ChildListViewModel was updated recently to accept array $children. We'll pass $result->items and also pass the paginator manually, or we can just send $result to view and wrap it.
        // Let's modify ChildListViewModel slightly or just pass data directly to view.

        $viewModel = new ChildListViewModel($result->items);

        // Wrap pagination for blade
        $paginator = new LengthAwarePaginator(
            items: $viewModel->children,
            total: $result->total,
            perPage: $result->perPage,
            currentPage: $result->currentPage,
            options: ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.children.index', [
            'paginator' => $paginator,
        ]);
    }
}
