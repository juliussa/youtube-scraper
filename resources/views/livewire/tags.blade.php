<div>
	@if($tags)
	    <ul class="list-group">
	    	@foreach($tags as $tag)
		    	<li class="list-group-item"><a wire:click.prevent="addTag({{ $tag->id }})">{{ $tag->title }}</a></li>
	    	@endforeach
	    </ul>
    @endif
</div>
