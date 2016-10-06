<a href="#delete" title="{{ $title }}" class="sw-confirm {{ isset($class) ? $class : "" }}" data-title="{{ $title }}"
   data-text="{{ isset($text) ? $text : trans('common.cannot_be_undone') }}"
   data-url="{{ $url }}" data-csrf="{{ csrf_token() }}"
   data-method="{{ isset($method) ? $method : 'delete' }}"
   data-confirm="{{ isset($confirm) ? $confirm : trans('common.delete') }}"
   data-cancel="{{ isset($cancel) ? $cancel : trans('common.cancel') }}">{!! $name !!}</a>
