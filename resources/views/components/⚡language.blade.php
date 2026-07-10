<?php

use Livewire\Component;

new class extends Component
{
    //

    public array $languages = ['ar','fr'];
    public string $currentLanguage = 'en';

    public function mount()
    {
        $this->languages       = config('saas.supported_languages');
        $this->currentLanguage = app()->getLocale();
    }

    public function changeLocale(string $localeCode)
    {
        auth()->user()->update(['locale' => $localeCode]);

        return redirect(request()->header('Referer'));
    }

};
?>

<div>
   <div>
     
    <div class="hidden bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48" id="lnaguage">
        @foreach($languages as $language)
            <a wire:click="changeLocale('{{ $language['short_code'] }}')" href="#" class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:text-indigo-600">
                {{ $language['title'] }}
            </a>
        @endforeach
    </div>
</div>


            <flux:sidebar.group expandable expanded=false heading="Language( {{ $currentLanguage }} )" class="grid">
                @foreach($languages as $language)
                    <flux:sidebar.item wire:click="changeLocale('{{ $language['short_code'] }}')"  >{{ $language['title'] }}</flux:sidebar.item>
                @endforeach
            </flux:sidebar.group>

 