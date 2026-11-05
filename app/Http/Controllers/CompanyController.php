<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function show(Company $company): View
    {
        abort_unless($company->is_published, 404);

        $company->load(['categories' => static function (BelongsToMany $query): void {
            $query->where('is_active', true);
        }]);

        return view('companies.show', ['company' => $company]);
    }
}
