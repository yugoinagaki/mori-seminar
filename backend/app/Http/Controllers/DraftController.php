<?php

namespace App\Http\Controllers;

use App\Models\AnnualTheme;
use App\Models\CaseStudy;
use App\Models\Draft;
use App\Models\Faq;
use App\Models\Member;
use App\Models\Post;
use App\Models\Professor;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class DraftController extends Controller
{
    private const MODEL_MAP = [
        'annual_theme' => AnnualTheme::class,
        'case_study'   => CaseStudy::class,
        'faq'          => Faq::class,
        'member'       => Member::class,
        'post'         => Post::class,
        'professor'    => Professor::class,
        'site_setting' => SiteSetting::class,
    ];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_type' => ['required', 'string', 'in:' . implode(',', array_keys(self::MODEL_MAP))],
            'model_id'   => ['nullable', 'integer'],
            'field'      => ['required', 'string', 'max:100'],
            'value'      => ['required', 'string'],
        ]);

        Draft::updateOrCreate(
            [
                'user_id'    => auth()->id(),
                'model_type' => $validated['model_type'],
                'model_id'   => $validated['model_id'],
                'field'      => $validated['field'],
            ],
            ['value' => $validated['value']]
        );

        $count = Draft::where('user_id', auth()->id())->count();

        return response()->json(['ok' => true, 'draft_count' => $count]);
    }

    public function apply()
    {
        $drafts = Draft::where('user_id', auth()->id())->get();

        foreach ($drafts as $draft) {
            $class = self::MODEL_MAP[$draft->model_type] ?? null;
            if (! $class) {
                continue;
            }

            $id    = $draft->model_id ?? 1;
            $model = $class::find($id);
            if (! $model) {
                continue;
            }

            $model->update([$draft->field => $draft->value]);
            $draft->delete();
        }

        return redirect()->back()->with('edit_applied', true);
    }

    public function discard()
    {
        Draft::where('user_id', auth()->id())->delete();

        return redirect()->back();
    }
}
