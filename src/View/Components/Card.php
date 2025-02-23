<?php

namespace WireUi\View\Components;

use Illuminate\Contracts\View\View;
use WireUi\Traits\Components\{HasSetupColor, HasSetupRounded};

class Card extends WireUiComponent
{
    use HasSetupColor;
    use HasSetupRounded;

    protected array $packs = ['shadow', 'padding'];

    protected array $props = [
        'title'      => null,
        'borderless' => false,
        'shadowless' => false,
    ];

    protected function processed(array $data): void
    {
        $this->title ??= data_get($data, 'title');
    }

    public function blade(): View
    {
        return view('wireui::components.card');
    }
}
