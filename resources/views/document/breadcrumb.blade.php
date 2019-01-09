<li><a href="{{ route('documents') }}">{{ trans('document.title.documents') }}</a></li>
@if ($document->documentType->parent->parent_id)
    <li><a href="{{ route('documents.documents_type', ['type' => $document->documentType->parent]) }}">{{ $document->documentType->parent->name }}</a></li>
@endif
