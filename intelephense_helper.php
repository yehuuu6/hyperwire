<?php

namespace Illuminate\Contracts\View;

use Illuminate\Contracts\Support\Renderable;

interface View extends Renderable
{
  /**
   * Set the title of the page for full page livewire components.
   */
  public function title(string $text);
}
