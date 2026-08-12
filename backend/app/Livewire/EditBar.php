<?php

namespace App\Livewire;

use App\Models\AnnualTheme;
use App\Models\CaseStudy;
use App\Models\Draft;
use App\Models\Faq;
use App\Models\Member;
use App\Models\Post;
use App\Models\Professor;
use App\Models\SiteSetting;
use Livewire\Component;

class EditBar extends Component
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

    public bool $editMode = false;
    public int $draftCount = 0;

    public function mount(): void
    {
        if (! auth()->check()) {
            return;
        }
        $this->editMode   = session('edit_mode', false);
        $this->draftCount = Draft::where('user_id', auth()->id())->count();
    }

    public function toggle(): void
    {
        $this->editMode = ! $this->editMode;
        session(['edit_mode' => $this->editMode]);
        $this->dispatch('edit-mode-changed', active: $this->editMode);
    }

    public function apply(): void
    {
        $drafts = Draft::where('user_id', auth()->id())->get();

        foreach ($drafts as $draft) {
            $class = self::MODEL_MAP[$draft->model_type] ?? null;
            if (! $class) {
                continue;
            }
            $model = $class::find($draft->model_id ?? 1);
            if (! $model) {
                continue;
            }
            $model->update([$draft->field => $draft->value]);
            $draft->delete();
        }

        $this->editMode   = false;
        $this->draftCount = 0;
        session(['edit_mode' => false]);
        $this->dispatch('edit-mode-changed', active: false);

        $this->redirect(request()->url());
    }

    public function discard(): void
    {
        Draft::where('user_id', auth()->id())->delete();

        $this->editMode   = false;
        $this->draftCount = 0;
        session(['edit_mode' => false]);
        $this->dispatch('edit-mode-changed', active: false);

        $this->redirect(request()->url());
    }

    public function render()
    {
        if (auth()->check()) {
            $this->draftCount = Draft::where('user_id', auth()->id())->count();
        }

        return view('livewire.edit-bar');
    }
}
